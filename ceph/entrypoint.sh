#!/bin/bash

set -e

# Variables
CLUSTER_NAME="ceph"
MON_NAME="mon-a"
MON_DATA_DIR="/var/lib/ceph/mon/${CLUSTER_NAME}-${MON_NAME}"
OSD_DATA_DIR="/var/lib/ceph/osd/ceph-0"
MDS_NAME="mds-a"
MDS_DATA_DIR="/var/lib/ceph/mds/${CLUSTER_NAME}-${MDS_NAME}"
RGW_NAME="rgw-a"
RGW_DATA_DIR="/var/lib/ceph/radosgw/${CLUSTER_NAME}-${RGW_NAME}"
FSID=$(uuidgen)

# Create Ceph configuration file
cat <<EOF > /etc/ceph/ceph.conf
[global]
fsid = ${FSID}
mon_initial_members = ${MON_NAME}
mon_host = 127.0.0.1
public_network = 0.0.0.0/0
cluster_network = 0.0.0.0/0
EOF

# Create necessary directories if they don't exist
mkdir -p ${MON_DATA_DIR} ${OSD_DATA_DIR} ${MDS_DATA_DIR} ${RGW_DATA_DIR}

# Bootstrap the monitor
if [ ! -f ${MON_DATA_DIR}/keyring ]; then
    ceph-authtool --create-keyring ${MON_DATA_DIR}/keyring --gen-key -n mon. --cap mon 'allow *'
    ceph-authtool ${MON_DATA_DIR}/keyring --gen-key -n client.admin --cap mon 'allow *' --cap osd 'allow *' --cap mds 'allow *'
    ceph-mon --mkfs -i ${MON_NAME} --keyring ${MON_DATA_DIR}/keyring --monmap=/etc/ceph/monmap
    ceph-mon -i ${MON_NAME} --public-addr=127.0.0.1:6789
    ceph auth add mon. ${MON_NAME} -i ${MON_DATA_DIR}/keyring
    ceph auth add client.admin -i ${MON_DATA_DIR}/keyring
fi

# Create an OSD
if [ ! -f ${OSD_DATA_DIR}/keyring ]; then
    ceph-osd --mkfs -i 0 --keyring /dev/null
    ceph auth get-or-create osd.0 osd 'allow *' mon 'allow profile osd' -o ${OSD_DATA_DIR}/keyring
    ceph osd crush add 0 1.0 host=$(hostname) root=default
fi

# Create an MDS
if [ ! -f ${MDS_DATA_DIR}/keyring ]; then
    ceph-mds -i ${MDS_NAME} --mkfs
    ceph auth get-or-create mds.${MDS_NAME} mds 'allow *' osd 'allow *' mon 'allow profile mds' -o ${MDS_DATA_DIR}/keyring
fi

# Start monitor, OSD, and MDS
ceph-mon -i ${MON_NAME}
ceph-osd -i 0
ceph-mds -i ${MDS_NAME}

# Create an RGW
if [ ! -f ${RGW_DATA_DIR}/keyring ]; then
    mkdir -p ${RGW_DATA_DIR}
    radosgw --mkpool
    radosgw-admin user create --uid="admin" --display-name="admin" --caps="users=*;buckets=*;metadata=*;usage=*;zone=*"
fi

# Start the RGW
radosgw -n client.rgw.${RGW_NAME} --rgw-frontends="civetweb port=7480"

# Keep the container running
exec "$@"