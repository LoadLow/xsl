<?php
namespace Genkgo\Xsl;

use Genkgo\Xsl\Exception\ReadOnlyStreamException;
use Genkgo\Xsl\Exception\StreamException;

/**
 * Class Stream
 * @package Genkgo\Xsl
 */
final class Stream
{
    /**
     *
     */
    const PROTOCOL = 'gxsl://';
    /**
     *
     */
    const HOST = 'localhost';
    /**
     *
     */
    const ROOT = '#root';
    /**
     * @var string
     */
    private $template;
    /**
     * @var int
     */
    private $position = 0;
    /**
     * @var resource
     */
    public $context;
    /**
     * @var string
     */
    private $path;

    /**
     * @param $path
     * @return bool
     * @throws StreamException
     */
    public function stream_open($path)
    {
        $this->path = $this->uriToPath($path);
        return true;
    }

    /**
     *
     */
    public function stream_close()
    {
        $this->template = null;
    }

    public function stream_stat()
    {
        return [];
    }

    /**
     * @param $count
     * @return string
     * @throws StreamException
     */
    public function stream_read($count)
    {
        $streamContext = stream_context_get_options($this->context);

        /** @var Transpiler $transpiler */
        if (isset($streamContext['gxsl']['transpiler'])) {
            $transpiler = $streamContext['gxsl']['transpiler'];

            if ($this->isRoot($this->path)) {
                $content = $transpiler->transpileRoot();
            } else {
                $content = $transpiler->transpileFile($this->path);
            }
        } else {
            if ($this->isRoot($this->path)) {
                throw new StreamException(
                    $this->path . ' does not exists without stream'
                );
            }
            $content = file_get_contents($this->path);
        }

        $bytes = substr($content, $this->position, $count);
        $this->position += $count;

        return $bytes;
    }

    /**
     * @throws ReadOnlyStreamException
     */
    public function stream_write()
    {
        throw new ReadOnlyStreamException();
    }

    /**
     * @param $path
     * @return array
     */
    public function url_stat($path)
    {
        $filename = $this->uriToPath($path);

        if ($this->isRoot($filename)) {
            return [];
        }

        return stat($filename);
    }

    /**
     * @return bool
     */
    public function stream_eof()
    {
        return true;
    }

    /**
     * @param $filename
     * @return bool
     */
    private function isRoot($filename)
    {
        return substr($filename, strlen(self::ROOT) * -1) === self::ROOT;
    }

    /**
     * @param string $uri
     * @return string
     */
    private function uriToPath ($uri) {
        $filename = urldecode(str_replace(self::PROTOCOL . self::HOST, '', $uri));
        if (PHP_OS === 'WINNT') {
            return ltrim($filename, '/');
        }

        return $filename;
    }
}
