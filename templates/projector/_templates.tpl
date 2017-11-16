{ignore}
<script id="model-template" type="text/html">
    <div :class="['model-item', model.highlight ? model.highlight : '']" :style="this.style" @mouseover="mouseover" @mouseout="mouseout">
        <div class="model-drag" @mousedown="mousedown">
            <div class="model-name">
                {{ model.name }}
                <span class="not-exists" v-if="!model.exists">
                    new
                </span>
            </div>
            <a href="#" class="remove" @click.prevent="remove">
                &times;
            </a>
        </div>
        <div class="model-head">
            <input type="text" v-model="model.name" placeholder="Модель">
            <input type="text" v-model="model.module" list="modules-datalist" placeholder="Модуль">
        </div>
        <div class="model-fields">
            <field v-for="(field, key) in model.fields" :field="field" :index="key" :key="key" @remove="removeField"></field>
        </div>
        <div class="model-append">
            <a href="#" class="append button" @click.prevent="appendField">
                Добавить поле
            </a>
        </div>
    </div>
</script>

<script id="relation-template" type="text/html">

</script>

<script id="field-template" type="text/html">
    <div class="field">
        <a :class="{ 'field-edit': true, 'not-exists': !field.exists, 'changes': field.changes }" href="#" @click.pevent="openEditor">
            <span class="name">
                {{ field.name ? field.name : '---' }}
            </span>
            <span class="info">
                {{ field.type ? field.type : '---' }}
                <span v-if="field.attributes['modelClass']" class="model-class">
                    <br/> {{ field.attributes['modelClass'] }}
                </span>
            </span>
        </a>
        <div class="actions">
            <a href="#" class="remove" @click.prevent="remove">
                &times;
            </a>
        </div>
        <div class="field-editor-wrapper" v-if="editor">
            <div class="field-editor">
                <a href="#" class="field-editor-close" @click.prevent="closeEditor">
                    &times;
                </a>
                <div class="editor-fields">
                    <div class="row">
                        <div class="column large-4">
                            <input type="text" v-model="field.name" placeholder="name">
                        </div>
                        <div class="column large-4">
                            <input type="text" v-model="field.type" list="fields-datalist" placeholder="type">
                        </div>
                        <div class="column large-4">
                            <input type="text" v-model="field.label" placeholder="label">
                        </div>
                    </div>
                    <div class="row">
                        <div class="column large-4">
                            <input type="checkbox" :id="uniqueName('editable')" :true-value="1" :false-value="0" v-model="field.editable">
                            <label :for="uniqueName('editable')">
                                editable
                            </label>
                        </div>
                        <div class="column large-4">
                            <input type="checkbox" :id="uniqueName('null')" :true-value="1" :false-value="0" v-model="field.null">
                            <label :for="uniqueName('null')">
                                null
                            </label>
                        </div>
                    </div>
                    <div class="row" v-for="(attribute, key) in attributes">
                        <div class="column large-4">
                            <label :for="uniqueName(key)">
                                {{ attribute.name }}
                            </label>
                        </div>
                        <div class="column large-4">
                            <template v-if="attribute.type == 'char' || attribute.type == 'int'">
                                <input type="text" :id="uniqueName(key)" v-model="field.attributes[key]">
                            </template>
                            <template v-else-if="attribute.type == 'bool'">
                                <input type="checkbox" :id="uniqueName(key)" :true-value="1" :false-value="0" v-model="field.attributes[key]">
                            </template>
                            <template v-else-if="attribute.type == 'model'">
                                <input type="text" :id="uniqueName(key)" v-model="field.attributes[key]" list="models-datalist">
                            </template>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column large-12">
                            <input type="text" v-model="field.hint" placeholder="hint">
                        </div>
                    </div>
                    <div class="row">
                        <div class="column large-12">
                            <div class="choices">
                                <h4>Choices</h4>

                                <div class="row" v-for="(choice, key) in field.choices">
                                    <div class="column large-3">
                                        <input type="text" placeholder="value" v-model="choice.value">
                                    </div>
                                    <div class="column large-4">
                                        <input type="text" placeholder="name" v-model="choice.name">
                                    </div>
                                    <div class="column large-4">
                                        <input type="text" placeholder="label" v-model="choice.label">
                                    </div>
                                    <div class="column large-1">
                                        <a href="#" class="button remove-choice" @click.prevent="removeChoice(key)">
                                            &times;
                                        </a>
                                    </div>
                                </div>

                                <a href="#" class="button append-choice" @click.prevent="appendChoice">
                                    Добавить
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

<script id="scheme-template" type="text/html">
    <div class="scheme-editor-wrapper">
        <div class="scheme-editor" @mousemove="mousemove" @mouseup="mouseup">
            <model v-for="(model, key) in models" :model="model" :key="key" :index="key" @dragstart="dragstart" @remove="remove" @highlight="highlight" @clone="clone"></model>
        </div>
        <div class="hide">
            <datalist id="modules-datalist">
                <option :value="module" v-for="module in modulesList">{{ module }}</option>
            </datalist>
            <datalist id="fields-datalist">
                <option :value="key" v-for="(field, key) in fields">{{ key }}</option>
            </datalist>
            <datalist id="models-datalist">
                <option :value="shortModelName(model)" v-for="model in models">{{ shortModelName(model) }}</option>
            </datalist>
        </div>
    </div>
</script>
{/ignore}