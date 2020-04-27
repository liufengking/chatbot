<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Blueprint\Configs;

use Commune\Blueprint\Configs\Nest\ProtocalOption;
use Commune\Support\Option\Option;

/**
 * 机器人多轮对话内核的配置.
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 *
 * @property-read string $id                    Ghost 的 Id, 必须纯字母
 * @property-read string $name                  Ghost 的名称. 任意表达
 *
 * ## 服务注册
 *
 * @property-read array $configProviders        需要绑定的配置服务.
 * @property-read array $procProviders          需要绑定的进程级服务.
 *  [   ServiceProvider::class,
 *      ServiceProvider1::class => [ configs ]]
 *
 * @property-read array $reqProviders           需要绑定的请求级服务
 *
 * ## 组件注册
 * @property-read array $components             默认绑定的组件.
 *  [   ComponentClass::class,
 *      ComponentClass1::class => [configs] ]
 *
 * ## 配置注册
 * @property-read array $options                默认绑定的 Option 单例
 *  [   OptionClass::class,
 *      OptionClass1::class => [ configs ] ]
 *
 * ## Session 配置
 *
 * @property-read int $sessionExpire            Session 的过期时间, 秒
 * @property-read int $sessionLockerExpire      Session 锁的过期时间, 为0 则不锁
 * @property-read ProtocalOption[] $protocals   Session 可以处理的协议.
 * [
 *     [
 *         'group' => 'name',
 *         'protocal' => Protocal::class,
 *         'handler' => Handler::class,
 *         'params' => [ param1, param2...]
 *     ],
 * ]
 *
 *
 *
 * # 多轮对话相关逻辑.
 *
 * @property-read string[] $sceneContextNames   场景对应的根路径.
 * @property-read string $defaultScene          默认场景.
 */
interface GhostConfig extends Option
{
}