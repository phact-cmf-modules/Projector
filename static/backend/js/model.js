Vue.component('model', {
    template: document.getElementById('model-template').innerHTML,
    props: ['model', 'index'],
    data: function () {
        return {
            drag: false
        }
    },
    computed: {
        style: function () {
            return {
                top: this.model.coordinates.y + 'px',
                left: this.model.coordinates.x + 'px'
            }
        }
    },
    methods: {
        selfCheck: function () {
            if (!this.model.coordinates) {
                this.model.coordinates = {
                    w: 0,
                    h: 0
                }
            }
            if (!this.model.name) {
                this.model.name = '';
            }
        },
        mousedown: function(e) {
            if (e.shiftKey) {
                this.$emit('clone', this.model);
            } else {
                this.drag = true;
                this.$emit('dragstart', e, this.model);
            }
        },
        mouseup: function(e) {
            this.drag = false;
            this.$emit('dragend', e, this.model);
        },
        remove: function () {
            if (confirm("Вы действительно хотите удалить эту модель?")) {
                this.$emit('remove', this.index);
            }
        },
        mouseout: function () {
            this.$emit('highlight', {});
        },
        mouseover: function () {
            var models = {};
            var vm = this;
            _.each(this.model.fields, function (field) {
                if (field.attributes && field.attributes['modelClass']) {
                    var type = '';
                    if (field.type.indexOf('Has') != -1) {
                        type = 'has';
                    } else if(field.type.indexOf('ManyTo') != -1) {
                        type = 'many';
                    } else if(field.type.indexOf('Foreign') != -1) {
                        type = 'foreign';
                    }
                    models[field.attributes['modelClass']] = type;
                }
            });
            models[this.model.module + '\\' + this.model.name] = 'self';
            this.$emit('highlight', models);
        },
        removeField: function (index) {
            this.model.fields.splice(index, 1);
        },
        appendField: function () {
            this.model.fields.push({
                name: '',
                label: '',
                type: '',
                editable: 1,
                null: 0,
                hint: '',
                choices: [],
                changes: 1,
                exists: 0
            });
        }
    },
    beforeMount: function () {
        this.selfCheck();
    },
    beforeUpdate: function () {
        this.selfCheck();
    }
});