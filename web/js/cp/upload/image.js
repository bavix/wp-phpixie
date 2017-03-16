// Объект настройки
var FileAPI = {
    debug: false // режим отладки
    , cors: true
    , staticPath: '/node_modules/fileapi/dist/' // путь до флешек
};

var fileEvent;
var createPreview;
var uploadFile;

window.addEventListener('DOMContentLoaded', function () {
    var cropper;

    var $modal = $('#modalUpload');

    $modal.on('shown.bs.modal', function () {
        cropper = new Cropper(document.getElementById('modalUploadImg'), {
            autoCropArea: 1,
            aspectRatio: NaN
        });
    }).on('hidden.bs.modal', function () {

        cropper.destroy();

    }).find('button.btn.btn-success').click(function () {

        $modal.modal('hide');

        if (fileEvent) {

            var cropData = cropper.getData();

            var selfImage = FileAPI.Image(fileEvent)
                .crop(cropData.x, cropData.y, cropData.width, cropData.height);

            // Загружаем файл на сервер
            uploadFile(selfImage.clone());

            // Строим preview для изображений
            createPreview(selfImage);

        }

    });
});

function uploadImage(uploadType) {

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

        // Получаем выбранный файл
        fileEvent = FileAPI.getFiles(evt)[0];
        createPreview = _createPreview;
        uploadFile = _uploadFile;

        if (fileEvent) {
            FileAPI.Image(fileEvent).get(function (err, image) {
                if (!err) {

                    if (image.tagName === 'CANVAS') {
                        $('#modalUploadImg').attr('src', image.toDataURL("img/png"));
                    }
                    else {
                        $('#modalUploadImg').attr('src', $(image).attr('src'));
                    }

                    $('#modalUpload').modal();
                }
            });
        }
    };

    // Функция создающая preview для изображения
    var _createPreview = function (img) {

        img.resize(previewOpts.width, previewOpts.height, 'max')
            .get(function (err, image) {
                // Если не было ошибок, то вставляем изображение
                if (!err) {
                    // Отчищаем контейнер от текущего изображения
                    preview.innerHTML = '';

                    // Вставляем новое
                    preview.appendChild(image);
                }
            });

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