Vue.component('field', {
    template: document.getElementById('field-template').innerHTML,
    props: ['field', 'index'],
    data: function () {
        return {
            editor: false,
            key: '' + Math.random().toString(36).substr(2, 9)
        }
    },
    computed: {
        attributes: function () {
            var fields = this.$root.fields;
            var type = this.field.type;
            var attributes = [];
            _.each(fields, function (field, name) {
                if (name == type && field.attributes) {
                    attributes = field.attributes;
                }
            });
            return attributes;
        }
    },
    methods: {
        remove: function () {
            if (confirm("Вы действительно хотите удалить это поле?")) {
                this.$emit('remove', this.index);
            }
        },
        openEditor: function () {
            this.editor = true;
        },
        closeEditor: function () {
            this.editor = false;
        },
        uniqueName: function (name) {
            return name + '-' + this.key;
        },
        selfCheck: function () {
            var vm = this;
            var attrs = this.field.attributes;
            var vmAttrs = vm.attributes;
            var found = [];
            var changes = false;

            _.each(vmAttrs, function (attr, name) {
                found.push(name);
                if (_.isUndefined(attrs[name])) {
                    attrs[name] = attr.default;
                    changes = true;
                }
            });
            for (var name in attrs) {
                if (_.isUndefined(vmAttrs[name])) {
                    changes = true;
                    delete attrs[name];
                }
            }
            // _.each(attrs, function(attr, key, list) {
            //     console.log(list);
            //     // console.log("LIST");
            //     // console.log(list);
            //     // console.log(attr);
            //     // if (_.isUndefined(vmAttrs[key])) {
            //     //     delete attrs[key];
            //     //     changes = true;
            //     // }
            // });
            if (changes) {
                this.$set(this.field, 'attributes', attrs);
            }
        },
        appendChoice: function () {
            this.field.choices.push({
                value: '',
                name: '',
                label: ''
            });
        },
        removeChoice: function (key) {
            this.field.choices.splice(key, 1);
        }
    },
    beforeMount: function() {
        if (_.isUndefined(this.field.attributes)) {
            this.$set(this.field, 'attributes', {});
        }
        if (_.isUndefined(this.field.choices)) {
            this.$set(this.field, 'choices', []);
        }
        this.selfCheck();
    },
    watch: {
        'field.type': function () {
            this.selfCheck();
        }
    }
});