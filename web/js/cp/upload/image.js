// Объект настройки
var FileAPI = {
    debug: true, // режим отладки
    staticPath: '/node_modules/fileapi/dist/' // путь до флешек
};

$(function () {

    // Ссылка на uploader
    var form = document.forms.userpic;

    // Ссылка на инпут, через который юзер будет выбирать файл
    var input = form.photo;

    // Ссылка на DOM-элемент, где будем отображать preview
    var preview = document.getElementById('preview');

    // Параметры предварительного просмотра
    var previewOpts = {
        width: 210
        , height: 210
    };

    // Параметры загрузки
    var uploadOpts = {
        url: '//cdn.wbs/api/upload/brands' // куда грузить
        , data: {} // дополнительный POST-параметры
        , name: 'filedata' // название POST-параметра загружаемого файла
        , activeClassName: 'upload_active' // класс, который будем добавлять общему контейнеру при загрузке
    };

    // Функция, которая будет срабатывать при выборе файла
    var _onSelectFile = function (evt/**Event*/) {

        // Получаем выбранный файл
        var file = FileAPI.getFiles(evt)[0];

        if (file) {
            // Строим preview для изображений
            _createPreview(file);

            // Загружаем файл на сервер
            _uploadFile(file);
        }
    };

    // Функция создающая preview для изображения
    var _createPreview = function (file/**File*/) {
        FileAPI.Image(file)
            .preview(previewOpts.width, previewOpts.height)
            .get(function (err, image) {
                // Если не было ошибок, то вставляем изображение
                if (!err) {
                    // Отчищаем контейнер от текущего изображения
                    preview.innerHTML = '';

                    // Вставляем новое
                    preview.appendChild(image);
                }
            })
        ;
    };

    // Функция загрузки файла на сервер
    var _uploadFile = function (file) {

        // Подготавливаем опции для загрузки
        var opts = FileAPI.extend(uploadOpts, {

            files: {},

            // событие "начало загрузки"
            upload: function () {
                form.className += ' ' + uploadOpts.activeClassName;
            },

            // событие "конец загрузки"
            complete: function (err, xhr) {
                form.className = (' ' + form.className + ' ').replace(' ' + uploadOpts.activeClassName + ' ', ' ');

                if (err) {
                    alert('Увы, произошла ошибка сервера.');
                } else {
                    // всё успешно загрузилось
                }
            }
        });

        // Добавляем файл, который будем загружать
        opts.files[opts.name] = file;

        // Загружаем на сервер
        FileAPI.upload(opts);
    };

    // Подписываемся на событие "change", оно будет срабатывать при выборе файла
    FileAPI.event.on(input, "change", _onSelectFile);

});