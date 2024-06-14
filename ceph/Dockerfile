# Use the official Ubuntu 20.04 as the base image
FROM ubuntu:20.04

# Set environment variables to avoid prompts during package installation
ENV DEBIAN_FRONTEND=noninteractive

# Update and install necessary packages
RUN apt-get update && \
    apt-get install -y \
    wget \
    curl \
    gnupg \
    lsb-release \
    sudo \
    uuid-runtime  # Install uuid-runtime for uuidgen

# Add Ceph repository and install packages
RUN wget -q -O- 'https://download.ceph.com/keys/release.asc' | apt-key add - && \
    echo deb https://download.ceph.com/debian-pacific $(lsb_release -sc) main | tee /etc/apt/sources.list.d/ceph.list && \
    apt-get update && \
    apt-get install -y --no-install-recommends \
    ceph \
    ceph-mds \
    ceph-mgr \
    ceph-mon \
    ceph-osd \
    radosgw && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Create necessary directories
RUN mkdir -p /var/lib/ceph/mon/ceph-a /var/lib/ceph/osd/ceph-0 /var/lib/ceph/mds/ceph-a /var/lib/ceph/radosgw

# Copy the entrypoint script
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Expose the RGW port
EXPOSE 7480

# Set the entrypoint
ENTRYPOINT ["./entrypoint.sh"]