<?php


namespace Framework\File\Enums;


use Framework\Support\Enum;

class FileType extends Enum
{
    public const IMAGE = 'image';
    public const DOCUMENT = 'document';
    public const FOLDER = 'folder';
    public const PDF = 'pdf';
    public const VIDEO = 'video';
    public const ZIP = 'zip';
    public const SOUND = 'sound';
    public const EXCEL = 'excel';
}
