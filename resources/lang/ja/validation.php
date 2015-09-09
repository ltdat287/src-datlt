<?php

return [
    'required'     => ':attributeを入力してください。',
    'max'          => [
        'string' => ':attributeは:max文字まで入力できます。'
    ],
    'vp_email'     => ':attributeまたはパスワードが誤っています。。',
    'password'     => ':attributeまたはパスワードが誤っています。',
    'confirmed'    => 'メールアドレスと:attributeが異なっています。',
    'unique'       => ':attributeは既に使用されています。',
    'vp_date'      => ':attributeは' . VP_DATE_MIN . 'から' . MemberHelper::getMaxDate() . 'までの範囲で入力してください。',
    'vp_telephone' => ':attributeには有効な電話番号を入力してください。',
    'date'         => ':attributeはYYYY-mm-dd形式で入力してください。',
    'between'      => [
        'string' => 'メールアドレスまたは:attributeが誤っています。',
    ],
    'user_not_delete_boss' => '部下が残っているBOSSを削除しようとしています。',
    'user_not_me_own'      => '次のデータには部下が残っています。削除するためには全ての部下を解除してください',
    'user_not_exists'      => '存在しないID：%sに対するアクセスがありました。',
    'deleted_id'           => '削除されたID：%sに対するアクセスがありました。',
    'not_direct_access'    => '確認画面を経由せずに直接参照されました。',
    'exists_employ_child'  => '参照データに部下が残っています。',
    'not_match_email'      => 'これらの資格情報は、当社の記録と一致しません。',
    'required_if'          => ':attributeを設定する場合、権限は従業員を選択する必要があります。',
    'attribute_exists'     => '必須入力項目：%sが入力されていません。',
    'date_format'          => ':attributeはYYYY-mm-dd形式で入力してください。',

    /**
     * Change attribute from name of input to placeholder
     */
    'attributes' => [
        'email'              => 'メールアドレス',
        'password'           => 'パスワード',
        'name'               => '名前',
        'kana'               => '名前（カナ）',
        'email_confirmation' => 'メールアドレス（確認',
        'telephone_no'       => '電話番号',
        'birthday'           => '生年月日',
        'note'               => 'ノート',
        'start_date'         => '生年月日（開始日)',
        'end_date'           => '生年月日（終了日)',
        'boss_id'            => 'BOSS',
        'use_role'           => '権限',
        'admin'              => '権限（管理者）',
        'boss'               => '権限（BOSS）',
        'employee'           => '権限（従業員）'
    ],

    'custom' => [
        'boss_id' => [
            'boss_with_employee' => ':attributeを設定する場合、権限は従業員を選択する必要があります。',
        ],
        'end_date' => [
            'start_to_end_date' => ':attributeは生年月日（開始日）と同じかそれ以降を入力してください。',
        ],
        'use_role' => [
            'employee_to_boss' => ':attributeをBOSS以外にするためには、部下を全て解除する必要があります。',
            'boss_to_employee' => ':attributeを従業員以外にするためには、BOSSで--を選択する必要があります。',
        ]
    ],
];
