<?php


namespace Framework\File\Enums;


use Framework\Support\Enum;

class FileType extends Enum
{
    const IMAGE = 'image';
    const DOCUMENT = 'document';
    const FOLDER = 'folder';
    const PDF = 'pdf';
    const VIDEO = 'video';
    const ZIP = 'zip';
    const SOUND = 'sound';
    const EXCEL = 'excel';
}