<?php

/**
 * Uses PHP's zlib.inflate filter to inflate deflate or gzipped content.
 *
 * This stream decorator skips the first 10 bytes of the given stream to remove
 * the gzip header, converts the provided stream to a PHP stream resource,
 * then appends the zlib.inflate filter. The stream is then converted back
 * to a Guzzle stream resource to be used as a Guzzle stream.
 *
 * @link http://tools.ietf.org/html/rfc1952
 * @link http://php.net/manual/en/filters.compression.php
 */
class puzzle_stream_InflateStream extends puzzle_stream_AbstractStreamDecorator implements puzzle_stream_MetadataStreamInterface
{
    public function __construct(puzzle_stream_StreamInterface $stream)
    {
        // Skip the first 10 bytes
        $stream = new puzzle_stream_LimitStream($stream, -1, 10);
        $resource = puzzle_stream_GuzzleStreamWrapper::getResource($stream);
        stream_filter_append($resource, 'zlib.inflate', STREAM_FILTER_READ);
        $this->stream = new puzzle_stream_Stream($resource);
    }
}
