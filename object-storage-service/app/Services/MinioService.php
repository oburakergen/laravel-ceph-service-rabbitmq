<?php


namespace App\Services;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class MinioService
{
    protected $s3Client;

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

    public function createBucket($bucketName)
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

    public function createUser($username, $password)
    {
        // MinIO does not support user management through the AWS SDK
        // You might need to use the MinIO Admin API to manage users
        // This is a placeholder function for creating users
    }
}