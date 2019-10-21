<?php

$messages = [

    'intro1' => <<<EOF
CommuneChatbot 是一个基于php的开源多轮对话开发框架. 可用于开发基于语音或文字通讯的多轮对话应用. 旨在让工程师开发多轮对话应用时, 像过去开发 web 网站, app 一样得心应手.
EOF
    ,
    'intro2' => <<<EOF
本项目的重点, 在于用工程化的手段为"复杂多轮对话"提供了一套解决方案. 相比现阶段对话机器人通常只实现一阶多轮对话, CommuneChatbot 能实现N阶多轮对话分形式嵌套, 跳转, 回归等功能点, 从而搭建多轮交互能力更完整的对话机器人.
EOF
    ,
    'intro3' => <<<EOF
此外本项目也致力实现工业级可用的对话机器人框架, 以良好的工程性作为首要原则. 为实现高性能使用了 swoole + 协程客户端; 设计了流畅的, 易于开发和阅读的api; 并且方方面面都考虑了高度组件化, 可配置. 
EOF
    ,
    'intro4' => <<<EOF
本项目着重于多轮对话管理, 并将 NLU (自然语言单元, 负责语音转换, 意图解析, 实体解析, 文本输出等) 作为第三方资源引用, 从而具备自然语言的能力.
EOF
    ,
    'intro5' => <<<EOF
CommuneChatbot 的应用方向可能有:

- 对话式OS
- 智能客服
- 在线问答
- 对话式交互游戏
- 智能音箱
- 语音机器人
- 对话式运维机器人
- 等等
EOF
    ,

    'chatbot' => [
        'info1' => '对话机器人是在语音平台 (例如小度, 小米, 天猫精灵等智能音箱), 即时通讯平台 (qq, 微信等) 上运行的机器人. 能在对话交互中听从用户的命令, 提供各种信息或执行各种任务.',

        'info2' => '早期的对话机器人, 主要出现在聊天场景, 例如早期的小冰. 随着自然语言技术的发展, 出现了越来越多的拟人对话机器人, 例如智能客服, AI电销等.

而 "siri" 和日渐流行的智能音箱, 智能蓝牙耳机等, 则可以操作其它智能软件和硬件. 从而初步具备了 "对话操作系统" 的雏形.',

        'info3' => '对话机器人未来发展方向, 应该是类似钢铁侠中"贾维斯"和"星期五"那样的智能助理, 从而带来全新的生产力. 这是由于:

- 语言是人类传递出信息最高效的方式
- 语音交互可以解放人的眼睛和双手',

        'info4' => '这在技术上需要在 "语音识别", "语义识别", "语言输出", "对话管理", "物联网交互" 等各个方面技术都充分发展.',

        'info5' => 'CommuneChatbot 项目致力于 "对话管理" 的领域, 希望在解决 "复杂多轮对话" 难题的前提下, 能快速开发出各种基于对话交互的应用, 探索对话交互的各种可能性.',

        'interface' => [

            'info1' => '现阶段在许多用户理解中, 对话机器人就是用来聊天的. 我个人认为对话机器人的本质是一种"交互方式", 和 "键盘 + 命令行", "鼠标 + 窗口", "触屏" 本质是一样的.

类似 "鼠标+键盘" 开创了桌面电脑的时代, "触屏" 开创了移动互联的时代, 我认为基于 "语音" 的对话也可能开启下一个时代.',

            'info2' => '而这些交互形式的本质, 都是让人去便捷地使用机器. "触屏" 空间上解放了人, 让人在马桶上也能操作智能设备. 而未来 "语音" 则能在姿势上解放人, 解放人的眼睛和双手, 通过智能无线耳机等设备, 与周围所有的智能设备互动.

所以本人认为 "聊天" 对于对话机器人而言, 不是充分必要的功能. 关键在于交互, 能够快速开发出各种应用, 操纵各种设备.',

            'info3' => '半个世纪来关于机器人的科幻设想, 许多语音交互还是基于关键字或指定语法的, 甚至有语音使用手册. 所以以交互为目的, 不该忘记了人自己超强的学习能力, 工程开发或许可以走在机器学习的前面.'

        ],
    ],



];

return [

    'demo' => [
        'simpleWiki' => $messages,
    ]

];
