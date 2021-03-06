<?php

namespace Commune\Chatbot\Config\Children;


use Commune\Support\Option;

/**
 * @property-read string $platformNotAvailable  平台不可用
 * @property-read string $chatIsTooBusy 输入太频繁
 * @property-read string $systemError 系统错误
 * @property-read string $unsupported 消息类型不支持
 * @property-read string $messageMissMatched 不明白的信息
 * @property-read string $farewell 退出会话, 和用户说再见
 * @property-read string $noHelpInfoExists 没有帮助信息
 * @property-read string $yes 默认的 "是" 的表达
 * @property-read string $no 默认的 "否" 的表达
 *
 */
class DefaultMessagesConfig extends Option
{
    public static function stub(): array
    {
        return [
            // 这些消息通常会经过 TranslateTemp 来渲染
            // 会调用 Translator 模块.
            // 真正回复给用户的文本通常在指定的翻译文件中.
            'platformNotAvailable' => 'system.platformNotAvailable',
            'chatIsTooBusy' => 'system.chatIsTooBusy',
            'unsupported' => 'system.unsupported',
            'systemError' => 'system.systemError',
            'farewell' => 'dialog.farewell',
            'noHelpInfoExists' => 'dialog.noHelpInfoExists',
            'messageMissMatched' => 'dialog.missMatched',
            'yes' => 'ask.yes',
            'no' => 'ask.no',
        ];
    }


}