<?php

namespace Asoc\Dadatata\Metadata;

interface ReaderInterface
{
    const HASH_SHA1 = 'sha1';
    const HASH_MD5 = 'md5';
    const HASH_SHA512 = 'sha512';

    const MIME = 'mime';
    const SIZE = 'size';

    const TEXT_TYPE = 'textType';

    const ARCHIVE_FILE_NUM = 'file_num';
    const ARCHIVE_FORMAT = 'format';

    const IMAGE_WIDTH = 'width';
    const IMAGE_HEIGHT = 'height';
    const IMAGE_X_RESOLUTION = 'x_resolution';
    const IMAGE_Y_RESOLUTION = 'y_resolution';
    const IMAGE_FORMAT = 'format';
    const IMAGE_ADDITIONAL = 'additional';
    const IMAGE_BIT_DEPTH = 'bitdepth';
    const IMAGE_COLORSPACE = 'colorspace';
    /**
     * Assoc array
     *   NAME1 = [x1, y1, x2, y2]
     *   NAME2 = [x1, y2, x2, y2]
     * ...
     */
    const IMAGE_PEOPLE = 'people';

    const AUDIO_LENGTH = 'length';
    const AUDIO_BITRATE = 'bitrate';
    const AUDIO_BITRATE_MODE = 'bitrate_mode';
    const AUDIO_CODEC = 'codec';
    const AUDIO_FORMAT = 'format';
    const AUDIO_SAMPLE_RATE = 'sample_rate';
    const AUDIO_ARTIST = 'artist';
    const AUDIO_TITLE = 'title';
    const AUDIO_ALBUM = 'album';
    const AUDIO_COMMENT = 'comment';
    const AUDIO_YEAR = 'year';
    const AUDIO_TRACK_NUM = 'trackNo';
    const AUDIO_CHANNELS = 'channels';

    const VIDEO_DURATION = 'duration';
    const VIDEO_BITRATE = 'bitrate';
    const VIDEO_BITRATE_MODE = 'bitrate_mode';
    const VIDEO_CODEC = 'codec';
    const VIDEO_FORMAT = 'format';
    const VIDEO_WIDTH = 'width';
    const VIDEO_HEIGHT = 'height';
    const VIDEO_ASPECT_RATIO = 'aspect_ratio';
    const VIDEO_FRAMERATE = 'framerate';
    const VIDEO_COLORSPACE = 'colorspace';
    const VIDEO_BIT_DEPTH = 'bitdepth';

    const DOCUMENT_PAGE_COUNT = 'pages';

    public function canHandle($mime);

    public function extract($path);
}