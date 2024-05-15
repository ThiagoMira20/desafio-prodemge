<?php

return [
    'required' => 'O campo :attribute é obrigatório.',
    'string' => 'O campo :attribute deve ser uma string.',
    'max' => [
        'string' => 'O campo :attribute não pode ter mais de :max caracteres.',
    ],
    'unique' => 'O :attribute já está registrado.',
    'email' => 'O campo :attribute deve ser um endereço de email válido.',
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
    'attributes' => [
        'name' => 'nome',
        'social_name' => 'nome social',
        'cpf' => 'CPF',
        'father_name' => 'nome do pai',
        'mother_name' => 'nome da mãe',
        'phone' => 'telefone',
        'email' => 'email',
    ],
];
