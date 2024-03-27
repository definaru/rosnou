<?php

namespace common\managers;

use yii;
use yii\web\UploadedFile;
use yii\web\BadRequestHttpException;

use Imagine\Imagick\Imagine;
use Imagine\Image\Point;
use Imagine\Image\Box;
use Imagine\Filter\Basic\Autorotate;

class UploadManager
{
    /**
     * Создаем из массива csv файл
     */
    public function getInput($name)
    {
        return UploadedFile::getInstanceByName($name);
    }

    /**
     * @param string $rootFolder
     * @param int $entityId
     * @param string $filename
     * @return array
     */
    public function getPath(string $rootFolder, string $filename)
    {
        $nodes = [
            $rootFolder,
            $filename
        ];

        $webpath = '/' . join('/', $nodes);

        return [
            'web' => $webpath,
            'file' => Yii::getAlias('@webroot') . $webpath,
        ];
    }

    /**
     * Сохраняем файл
     */
    public function saveFile(UploadedFile $File, string $rootFolder, $filename = null)
    {
        if ($File->getHasError()) {
            throw new BadRequestHttpException('Не удалось загрузить файл');
        }

        $path = $this->getPath($rootFolder, $filename ?? $File->name);

        @mkdir(dirname($path['file']), 0777, true);
        $result = $File->saveAs($path['file']);

        return [
            'web' => $path['web'],
            'file' => $path['file'],
            'result' => $result,
        ];
    }

    /**
     * Проверяем размер файла и выбрасываем исключение
     */
    public function sizeException(UploadedFile $File, int $mbsize)
    {
        if ($File->size > $mbsize * 1024 * 1024) {
            throw new BadRequestHttpException('Размер загружаемого файла должен быть не более ' . $mbsize . ' мб');
        }
    }

    /**
     * Проверяем тип файла и выбрасываем исключение
     */
    public function typeException(UploadedFile $File, string $type)
    {
        $ext = ['gif', 'jpeg', 'jpg', 'png'];

        if ($type == 'image') {

            if (!in_array(strtolower($File->getExtension()), $ext)) {
                throw new BadRequestHttpException('Можно загружать только файлы следующих типов: ' . join(', ', $ext));
            }
        }
    }

    /**
     * Вырезаем аватарку
     */
    public function cropAvatar($sourceFile, $destFile, $cropPoint, $cropBox, $resizeBox)
    {
        $imagine = new Imagine();
        $cropPoint = new Point($cropPoint[0], $cropPoint[1]);
        $cropBox = new Box($cropBox[0], $cropBox[1]);
        $resizeBox = new Box($resizeBox[0], $resizeBox[1]);
        $rotateFilter = new Autorotate();

        $rotateFilter
            ->apply($imagine->open($sourceFile))
            ->crop($cropPoint, $cropBox)
            ->resize($resizeBox)
            ->save($destFile);

        return $destFile;
    }
}