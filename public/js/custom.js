var dragArea = document.querySelector('.dragArea');
let inputfile = document.querySelector('.inputfile');
let drag = document.querySelector('.drag');

if (dragArea) {
    dragArea.addEventListener('dragover', function(event) {
        event.preventDefault();
        dragArea.classList.add('dragAreaActive');
        drag.classList.add('dragactive');
    });

    dragArea.addEventListener('dragleave', function() {
        // event.preventDefault();
        dragArea.classList.remove('dragAreaActive');
        drag.classList.remove('dragactive');
    });

    dragArea.addEventListener('drop', function(event) {
        event.preventDefault();
        dragArea.classList.add('dragAreaActive');

        inputfile.files = null;

        inputfile.files = event.dataTransfer.files;

        previewImage = event.dataTransfer.files[0];

        file_diplay();
    });

    dragArea.addEventListener('click', function(event) {
        inputfile.click();
    });

    inputfile.addEventListener('change', function() {
        previewImage = this.files[0];
        if (previewImage !== undefined) {
            dragArea.classList.add('dragAreaActive');
            file_diplay();
        }
    });
}

//   start file display function
function file_diplay() {
    let filetype = previewImage.type;
    let validextention = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
    if (validextention.includes(filetype)) {
        let fileReader = new FileReader();
        fileReader.onload = () => {
            let fileUrl = fileReader.result;
            // console.log(fileUrl);
            let imgTag = `
         <a class="btn btn-primary change"> <i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i></a>
          <img class="removeimage" src="${fileUrl}" alt="">`;
            dragArea.innerHTML = imgTag;
        };

        fileReader.readAsDataURL(previewImage);
    } else {
        alert('This file not image!');
    }
}
//   end file display function

// content view template
function getTypeHTML(key, inputName) {
    const TypeHtml = {
        label: `<div class="form-group"><label> Label</label> <input name="fields[${key}][label]" type="text" class="form-control"> </div>`,
        required: `<div class="form-group"><label> Required</label> <select name="fields[${key}][required]" class="form-control"> <option value="true">True</option><option value="false">False</option></select></div>`,
        min: `<div class="form-group"><label>Min</label><input name="fields[${key}][min]" type="number" class="form-control"> </div>`,
        max: `<div class="form-group"><label>Max</label><input name="fields[${key}][max]" type="number" class="form-control"> </div>`,
        minlength: `<div class="form-group"><label >Min (length)</label> <input name="fields[${key}][minlength]" type="number" class="form-control"> </div>`,
        maxlength: `<div class="form-group"><label >Max (length)</label> <input name="fields[${key}][maxlength]" type="number" class="form-control"> </div>`,
        max_file: `<div class="form-group"><label>Max Files</label><input name="fields[${key}][max_file]" type="text" class="form-control"> </div>`,
        size: `<div class="form-group"><label>Max Size</label><input name="fields[${key}][size]" type="number" placeholder="KB" class="form-control"> </div>`,

    };

    return TypeHtml[inputName];
}

// content type properties
const FieldTypes = {
    TEXT: {
        label: getTypeHTML('TEXT', 'label'),
        required: getTypeHTML('TEXT', 'required'),
        maxlength: getTypeHTML('TEXT', 'maxlength'),
        minlength: getTypeHTML('TEXT', 'minlength'),
    },
    NUMBER: {
        label: getTypeHTML('NUMBER', 'label'),
        required: getTypeHTML('NUMBER', 'required'),
        min: getTypeHTML('NUMBER', 'min'),
        max: getTypeHTML('NUMBER', 'max'),

    },
    EMAIL: {
        label: getTypeHTML('EMAIL', 'label'),
        required: getTypeHTML('EMAIL', 'required'),
    },
    DATE: {
        label: getTypeHTML('DATE', 'label'),
        required: getTypeHTML('DATE', 'required'),

    },
    TIME: {
        label: getTypeHTML('TIME', 'label'),
        required: getTypeHTML('TIME', 'required'),
    },
    TEXTAREA: {
        label: getTypeHTML('TEXTAREA', 'label'),
        required: getTypeHTML('TEXTAREA', 'required'),

    },
    IMAGE: {
        label: getTypeHTML('IMAGE', 'label'),
        required: getTypeHTML('IMAGE', 'required'),
        max_file: getTypeHTML('IMAGE', 'max_file'),
        size: getTypeHTML('IMAGE', 'size'),
    },
    EDITOR: {
        label: getTypeHTML('EDITOR', 'label'),
        required: getTypeHTML('EDITOR', 'required'),
    },
};

// content select rendering
const holder = $('#field_list');
const fieldSelector = $('#select_field');
let fieldCount = 1;

// add types to options
let fOptions = '';
for (let typeOption of Object.keys(FieldTypes)) {
    fOptions += `<option value="${typeOption}">${typeOption}</option>`;
}
fieldSelector.find('select').append(fOptions);

fieldSelector.find('button').on('click', function(e) {
    e.preventDefault();
    let selectedFieldKey = fieldSelector.find('select').val();
    if (!selectedFieldKey) return;

    let selectedField = FieldTypes[selectedFieldKey];

    addField(holder, selectedFieldKey, selectedField);
});

$(document).on('click', '#field_list .box-tools .btn', function(e) {
    e.preventDefault();
    let field = $(this).closest('.col-md-12');
    field.remove();
});

function addField(holder, key, fields) {
    let filedHtml = `<div class="col-md-12"> <div class="box box-comments border-left border-right" style="border-top: 3px solid #b9b9b9;"> <div class="box-header"> <h3 class="box-title">Field ${fieldCount} (${key})</h3> <div class="box-tools"> <a class="btn btn-sm btn-default"><i class="fa fa-times" aria-hidden="true"></i></a> </div> </div> <div class="box-body">`;
    for ([key, field] of Object.entries(fields)) {
        filedHtml += field;
    }
    fieldCount++;

    filedHtml += ` </div></div></div>`;
    holder.append(filedHtml);
}






       
   