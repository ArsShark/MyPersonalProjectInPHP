<?php
// lang/ru/validation.php

return [
    'min' => [
        'string' => 'Поле :attribute должно быть не короче :min символов.',
    ],
    'required' => 'Поле :attribute обязательно для заполнения.',
    'unique' => 'Такое значение поля :attribute уже существует.',
    'max' => [
        'string' => 'Поле :attribute не должно превышать :max символов.',
        'file' => 'Размер файла :attribute не должен превышать :max килобайт.',
    ],
    'image' => 'Файл :attribute должен быть изображением (jpeg, png, bmp, gif, svg, или webp).',
    'exists' => 'Выбранное значение для :attribute некорректно.',

    'attributes' => [
        'title' => 'Заголовок',
        'content' => 'Содержание',
        'category_id' => 'Категория',
        'image' => 'Изображение',
    ],
];