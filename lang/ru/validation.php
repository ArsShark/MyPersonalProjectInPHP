<?php

return [
    'min' => [
        'string' => 'Поле :attribute должно быть не короче :min символов.',
    ],
    'required' => 'Поле :attribute обязательно для заполнения.',
    'unique'   => 'Такое значение поля :attribute уже существует.',
    'max' => [
        'string' => 'Поле :attribute не должно превышать :max символов.',
        'file'   => 'Размер файла в поле :attribute не должен превышать :max килобайт.',
    ],
    'image'  => 'Файл в поле :attribute должен быть изображением (jpeg, png, bmp, gif, svg или webp).',
    'exists' => 'Выбранное значение для :attribute некорректно.',
    'array'  => 'Поле :attribute должно быть массивом.',
    'file'   => 'Поле :attribute должно быть файлом.',
    'mimes'  => 'Поле :attribute должно быть файлом одного из типов: :values.',
    'no_spaces' => 'Имя файла в поле :attribute не должно содержать пробелов.',

    'attributes' => [
        'title'       => 'Заголовок',
        'content'     => 'Содержание',
        'category_id' => 'Категория',
        'image'       => 'Обложка',
        'files.*'     => 'Прикреплённый файл',
    ],
];