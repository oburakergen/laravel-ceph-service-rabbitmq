<?php


namespace App\Services;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class MinioService
{
    protected S3Client $s3Client;

    public function __construct()
    {
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    /**
     * @throws \Exception
     */
    public function createBucket(string $bucketName)
    {
        try {
            $result = $this->s3Client->createBucket([
                'Bucket' => $bucketName,
            ]);
            return $result;
        } catch (AwsException $e) {
            throw new \Exception('Error creating bucket: ' . $e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function uploadObject(string $bucketName, $key, $filePath)
    {
        try {
            $result = $this->s3Client->putObject([
                'Bucket' => $bucketName,
                'Key'    => $key,
                'SourceFile' => $filePath,
            ]);
            return $result;
        } catch (S3Exception $e) {
            throw new \Exception('Error uploading object: ' . $e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteObject($bucketName, $key)
    {
        try {
            $result = $this->s3Client->deleteObject([
                'Bucket' => $bucketName,
                'Key'    => $key,
            ]);
            return $result;
        } catch (S3Exception $e) {
            throw new \Exception('Error deleting object: ' . $e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function checkBucketSize($bucketName, $maxSize)
    {
        try {
            $objects = $this->s3Client->listObjectsV2([
                'Bucket' => $bucketName,
            ]);

            $totalSize = 0;

            foreach ($objects['Contents'] as $object) {
                $totalSize += $object['Size'];
            }

            return $totalSize;
        } catch (S3Exception $e) {
            throw new \Exception('Error checking bucket size: ' . $e->getMessage());
        }
    }
}