function uploadImages(uploadType) {

    // Ссылка на uploader
    var form = document.forms.images;

    // Ссылка на инпут, через который юзер будет выбирать файл
    var input = form.photo;

    var host = location.host;

    if (host.indexOf('www.') === 0) {
        host = host.replace('www.', '');
    }

    // Параметры загрузки
    var uploadOpts = {
        url: location.protocol + '//cdn.' + host + '/api/upload/' + uploadType + '?' + $(form).serialize() // куда грузить
        , data: {} // дополнительный POST-параметры
        , name: 'filedata' // название POST-параметра загружаемого файла
        , activeClassName: 'upload_active' // класс, который будем добавлять общему контейнеру при загрузке
    };

    // Функция, которая будет срабатывать при выборе файла
    var _onSelectFile = function (evt/**Event*/) {

        _uploadFile(FileAPI.getFiles(evt));

        // var files = FileAPI.getFiles(evt);
        //
        // FileAPI.Image(files).get(function (err, image) {
        //     if (!err) {
        //         _uploadFile(image);
        //     }
        // });

        // for (var i in files) {
        //
        //     if (!files.hasOwnProperty(i)) {
        //         continue;
        //     }
        //
        //     var fileEvent = files[i];
        //
        //     if (fileEvent) {
        //         FileAPI.Image(fileEvent).get(function (err, image) {
        //             if (!err) {
        //                 _uploadFile(image);
        //             }
        //         });
        //     }
        //
        // }


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
                }
                else {
                    // всё успешно загрузилось

                    alert('Фотография загружена. После обработки и оптимизации фото будет добавлено.')
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

}