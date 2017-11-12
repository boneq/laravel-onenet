<?php

return [
    //Token（令牌）
    'token'=>env('OneNet_Token','xxxx'),
    //EncodingAESKey（消息加解密秘钥）
    'encodekey'=>env('OneNet_EncodingAESKey', 'xxxx'),
    //OneNet_APIKey(设备操作中的密钥)
    'apikey'=>env('OneNet_APIKey','xxx')
];
