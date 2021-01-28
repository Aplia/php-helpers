<?php
namespace Aplia\Support;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * A logger handler which sends all logs to eZDebug.
 *
 * It will only pass logs if it finds the class `eZDebug`.
 */
class LoggerAdapter implements LoggerInterface
{
    public static function interpolate($message, array $context = [])
    {
        if (strpos($message, '{') === false) {
            foreach ($context as $key => $val) {
                $message .= "\n" . $key . '=' . $val;
            }
            return $message;
        }
        // build a replacement array with braces around the context keys
        $replace = [];
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }

    /**
     * System is unusable.
     */
    public function emergency($message, array $context = [])
    {
        if (!class_exists('eZDebug')) {
            return;
        }
        \eZDebug::writeError(self::interpolate($message, $context));
    }

    /**
     * Action must be taken immediately.
     * @return null
     */
    public function alert($message, array $context = [])
    {
        if (!class_exists('eZDebug')) {
            return;
        }
        \eZDebug::writeError(self::interpolate($message, $context));
    }

    /**
     * Critical conditions.
     * @return null
     */
    public function critical($message, array $context = [])
    {
        if (!class_exists('eZDebug')) {
            return;
        }
        \eZDebug::writeError(self::interpolate($message, $context));
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     */
    public function error($message, array $context = [])
    {
        if (!class_exists('eZDebug')) {
            return;
        }
        \eZDebug::writeError(self::interpolate($message, $context));
    }

    /**
     * Exceptional occurrences that are not errors.
     * @return null
     */
    public function warning($message, array $context = [])
    {
        if (!class_exists('eZDebug')) {
            return;
        }
        \eZDebug::writeWarning(self::interpolate($message, $context));
    }

    /**
     * Normal but significant events.
     */
    public function notice($message, array $context = [])
    {
        if (!class_exists('eZDebug')) {
            return;
        }
        \eZDebug::writeNotice(self::interpolate($message, $context));
    }

    /**
     * Interesting events.
     */
    public function info($message, array $context = [])
    {
        if (!class_exists('eZDebug')) {
            return;
        }
        \eZDebug::writeNotice(self::interpolate($message, $context));
    }

    /**
     * Detailed debug information.
     */
    public function debug($message, array $context = [])
    {
        if (!class_exists('eZDebug')) {
            return;
        }
        \eZDebug::writeDebug(self::interpolate($message, $context));
    }

    /**
     * Logs with an arbitrary level.
     */
    public function log($level, $message, array $context = [])
    {
        if (!class_exists('eZDebug')) {
            return;
        }
        if ($level == LogLevel::EMERGENCY) {
            $this->emergency($message, $context);
        } elseif ($level == LogLevel::ALERT) {
            $this->alert($message, $context);
        } elseif ($level == LogLevel::CRITICAL) {
            $this->critical($message, $context);
        } elseif ($level == LogLevel::ERROR) {
            $this->error($message, $context);
        } elseif ($level == LogLevel::WARNING) {
            $this->warning($message, $context);
        } elseif ($level == LogLevel::NOTICE) {
            $this->notice($message, $context);
        } elseif ($level == LogLevel::INFO) {
            $this->info($message, $context);
        } elseif ($level == LogLevel::DEBUG) {
            $this->debug($message, $context);
        }
    }
}
