<?php


namespace Commune\Chatbot\App\Drivers\Demo;

use Commune\Chatbot\App\Drivers\Psr16CacheAdapter;
use Commune\Chatbot\Blueprint\Conversation\Conversation;
use Commune\Chatbot\Contracts\CacheAdapter;
use Commune\Chatbot\Framework\Conversation\RunningSpyTrait;
use Psr\SimpleCache\CacheInterface;

/**
 * 模拟的cache. 方便测试用.
 */
class ArrayCache implements CacheAdapter
{
    use RunningSpyTrait;

    protected static $cached = [];

    protected $logger;

    /**
     * @var string
     */
    protected $traceId;

    /**
     * @var CacheInterface
     */
    protected $psrCache;

    public function __construct(Conversation $conversation)
    {
        $this->logger = $conversation->getLogger();
        $this->traceId = $conversation->getTraceId();
        self::addRunningTrace($this->traceId, $this->traceId);
    }


    public function set(string $key, string $value, int $ttl = null): bool
    {
        self::$cached[$key] = $value;
        return true;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, self::$cached);
    }

    public function get(string $key): ? string
    {
        return self::$cached[$key] ?? null;
    }


    public function lock(string $key, int $ttl = null): bool
    {
        return true;
    }

    public function forget(string $key): bool
    {
        $i = isset(self::$cached[$key]);
        unset(self::$cached[$key]);
        return $i;
    }

    public function unlock(string $key): bool
    {
        return $this->forget($key);
    }

    public function getPSR16Cache(): CacheInterface
    {
        return $this->psrCache
            ?? $this->psrCache = new Psr16CacheAdapter($this);
    }

    public function getMultiple(array $keys, $default = null): array
    {
        $results = [];
        foreach ($keys as $key) {
            $results[$key] = $this->get($key) ?? $default;
        }
        return $results;
    }

    public function setMultiple(array $values, int $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set(strval($key), $value, $ttl);
        }
        return true;
    }

    public function delMultiple(array $keys): bool
    {
        foreach ($keys as $key) {
            $this->forget($key);
        }
        return true;
    }


    public function __destruct()
    {
        self::removeRunningTrace($this->traceId);
    }

}