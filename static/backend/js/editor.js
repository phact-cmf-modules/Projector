Vue.component('editor', {
    template: document.getElementById('scheme-template').innerHTML,
    props: ['models', 'fields', 'modules'],
    data: function () {
        return {
            drag: {
                drag: false,
                model: null,
                clientX: 0,
                clientY: 0,
                position: { top: 0, left: 0 }
            }
        }
    },
    computed: {
        modulesList: function () {
            var loaded = this.modules;
            _.each(this.models, function (model) {
                if (_.indexOf(loaded, model.module) == -1) {
                    loaded.push(model.module);
                }
            });
            return loaded;
        }
    },
    methods: {
        dragstart: function (e, model) {
            this.drag.model = model;
            this.drag.drag = true;
            this.drag.x = e.x;
            this.drag.y = e.y;

            var $frame = $(e.target).closest('.model-item').parent().parent();
            var pos = $(e.target).closest('.model-item').position();

            this.drag.position.left = $frame.scrollLeft() + pos.left;
            this.drag.position.top = $frame.scrollTop() + pos.top;
        },
        mouseup: function (e, model) {
            if (this.drag.drag) {
                this.drag.drag = false;
            }
        },
        mousemove: function (e) {
            if (this.drag.drag) {
                var x = this.drag.x - e.x;
                var y = this.drag.y - e.y;
                this.drag.model.coordinates.x = this.drag.position.left - x;
                this.drag.model.coordinates.y = this.drag.position.top - y;
            }
        },
        remove: function (index) {
            this.models.splice(index, 1);
        },
        appendModel: function () {
            console.log('APPEND MODEL');
            this.models.push({
                name: '',
                module: '',
                fields: [],
                attributes: [],
                coordinates: {
                    x: 0,
                    y: 0
                }
            });
        },
        highlight: function (modelsFullName) {
            var vm = this;
            _.each(vm.models, function (model) {
                vm.$set(model, 'highlight', '');
            });
            _.each(modelsFullName, function (highlight, modelFullName) {
                var data = modelFullName.split('\\');
                var moduleName = data[0];
                var modelName = data[1];
                _.each(vm.models, function (model) {
                    if (modelName == model.name && moduleName == model.module) {
                        vm.$set(model, 'highlight', 'highlight-' + highlight);
                    }
                });
            });
        },
        saveData: function (sync) {
            if (sync) {
                var $form = $('<form method="post" class="hide"/>');
                var $data = $("<textarea name='models'/>").val(JSON.stringify(this.models));
                $('body').append($form);
                $form.append($data);
                if (sync) {
                    $form.append($('<input name="sync" value="1"/>'))
                }
                $form.submit();
            } else {
                $.ajax({
                    type: 'post',
                    data: {
                        models: JSON.stringify(this.models)
                    }
                })
            }

        },
        shortModelName: function (model) {
            return model.module + '\\' + model.name;
        }
    },
    mounted: function () {
        var $editor = $('.scheme-editor');
        var $wrapper = $('.scheme-editor-wrapper');
        var vm = this;

        $(window).on('resize', function () {
            $wrapper.css({
                width: $(window).width() - 278,
                height: $(window).height()
            });
        }).trigger('resize');

        $(window).on('keypress', function (e) {

            if (e.key == "n" && e.ctrlKey) {
                vm.appendModel();
            }

            if (e.key == "S" && e.ctrlKey && e.shiftKey) {
                vm.saveData(true);
            }
        });
    },
    watch: {
        models: {
            handler: _.debounce(function (val, oldVal) {
                this.saveData(false);
            }, 500),
            deep: true
        }
    }
});