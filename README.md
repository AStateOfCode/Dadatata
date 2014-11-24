# Dadatata

Yeah, weird name :) Dadatata contains wrappers and libraries to work with media files using various common tools.

Dadatata includes:

* Database/backend agnostic model layer.
* Metadata extraction.
* Wrappers and command builders around common tools, eg. ffmpeg or unoconv.
* Filters for transformations, eg. image resize or pdf convert.
* Filter pipeline, ie. chainable filters.
  * Example: ODT file -> convert to PDF with unoconv -> create thumbnails with ImageMagick

This project is neither finished nor feature complete. Since I currently do not use it extensively, development may be slow or stalled. Contributions are, of course, welcome!

## File categories

The categories below are identified by the metadata extractors and mime type guessers. Based on this information, the various filters decide if they can be applied to a given file or not.

* Audio
* Video
* Image
* Document
* Text
* None

## License

MIT