{extends 'admin/base.tpl'}

{block 'seo'}
    {parent}
    <script src="https://unpkg.com/vue"></script>
    {include 'projector/_templates.tpl'}
{/block}

{block 'main_block'}
    {ignore}
        <div id="scheme-editor">
            <editor :models="models" :fields="fields" :modules="modules"></editor>
        </div>
    {/ignore}
{/block}

{block 'js'}
    <script>
        var schemeData = {raw $data|json_encode};
        $(function () {
            var app = new Vue({
                el: '#scheme-editor',
                data: schemeData
            });
        });
    </script>

    <style>
        .scheme-editor-wrapper {
            overflow: scroll;
            position: relative;
            user-select: none;
        }
        .scheme-editor {
            min-width: 3000px;
            min-height: 3000px;
        }

        .model-item {
            position: absolute;
            width: 170px;
            background-color: #fff;
            box-shadow: 0 0 0 1px #DFDFDF;
        }

        .model-item.highlight-foreign {
            box-shadow: 0 0 0 2px #FC585E;
        }

        .model-item.highlight-self {
            box-shadow: 0 0 0 2px #3FC265;
        }

        .model-item.highlight-many {
            box-shadow: 0 0 0 2px #01BCD9;
        }

        .model-item.highlight-has {
            box-shadow: 0 0 0 2px #A289E0;
        }

        .model-item .model-head {
            display: flex;
        }

        .model-item .model-drag .not-exists {
            padding: 2px 4px;
            color: #fff;
            border-radius: 3px;
            background-color: #ff8100;
            font-weight: bold;
        }

        .model-item .model-head input {
            width: 100%;
            height: 30px;
            font-size: 12px;
            border-right: none;
            border-left: none;
            border-bottom: 1px solid #DFDFDF;
            color: #bbb;
        }

        .model-item .model-head input:last-child {
            border-left: 1px solid #DFDFDF;
        }

        .model-drag {
            position: relative;
            padding: 6px 10px;
            cursor: move;
        }

        .model-drag .model-name {
            font-size: 12px;
        }

        .model-drag .remove {
            text-decoration: none;
            position: absolute;
            right: 0;
            top: 0;
            color: #FC585E;
            height: 25px;
            line-height: 25px;
            width: 25px;
            text-align: center;
        }

        .model-append {
            position: relative;
        }

        .model-append .append {
            position: absolute;
            left: -2px;
            right: -2px;
            height: 30px;
            line-height: 30px;
            display: none;
            top: 99%;
        }

        .model-item:hover .model-append .append {
            display: block;
            text-align: center;
        }

        .model-fields .field {
            position: relative;
        }

        .model-fields .field {
            position: relative;
            height: 25px;
            line-height: 25px;
        }

        .field-edit {
            display: block;
            text-decoration: none;
            color: #000;
            font-size: 12px;
            padding-left: 10px;
            position: relative;
        }

        .field-edit.not-exists {
            background-color: #ffeedf;
        }

        .field-edit .info {
            display: none;
            position: absolute;
            right: 100%;
            z-index: 10;
            background-color: #fff;
            padding: 7px;
            width: 150px;
            top: 0;
            line-height: 1;
            box-shadow: 0 0 6px 1px rgba(0,0,0,0.1);
        }

        .field-edit .info .model-class {
            font-weight: bold;
        }

        .field-edit:hover {
            background-color: #f3f3f3;
        }

        .field-edit:hover .info {
            display: block;
        }


        .model-fields .field .remove {
            text-decoration: none;
            position: absolute;
            right: 0;
            top: 0;
            color: #FC585E;
            height: 25px;
            line-height: 25px;
            width: 25px;
            text-align: center;
        }

        .field-editor-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.6);
            z-index: 100;
        }

        .field-editor {
            margin: 100px auto;
            background-color: #fff;
            padding: 30px;
            position: relative;
            width: 900px;
        }

        .field-editor-close {
            color: #fff;
            width: 50px;
            height: 50px;
            font-size: 50px;
            position: absolute;
            right: -40px;
            top: -10px;
            text-decoration: none;
            text-align: right;
            line-height: 1;
        }

        .field-editor input[type=text] {
            width: 100%;
        }

        .field-editor .row {
            margin: 0 -15px 15px;
        }

        .field-editor .row:last-child {
            margin-bottom: 0;
        }

    </style>
{/block}