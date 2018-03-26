// File Upload initialization
$('#file').bootstrapFileField({

    // label text for file input
    label: "انتخاب ...",

    // default button class
    btnClass: 'btn btn-default btn-block',

    // enable/disable file preview
    preview: 'on',

    // restric file types by mime type
    fileTypes: false,

    // max/min file size
    maxFileSize: false,
    minFileSize: false,

    // max totle size
    maxTotalSize: false,

    // max/min number of files
    maxNumFiles: false,
    minNumFiles: false

});

// --