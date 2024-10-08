<template>
    <div class="mb-1 col-12 col-sm">
        <label :for="cssId" class="control-label">{{ label }}</label>
        <select :id="cssId" class="form-control" v-if="type == 'select' || type == 'select2'" v-model="input" @change="filter(name, input)" :multiple="multiple">
            <option v-if="!multiple" value="">-- {{ label }} --</option>
            <option v-for="(value, id) in values" :value="id" :key="id">{{ value }}</option>
        </select>
        <input autocomplete="off" type="text" class="form-control" v-else :id="cssId" v-model="input" @keydown.prevent.enter @keyup="debouncedFilter(name, input)" :placeholder="label">
    </div>
    <div class="w-100 clearfix" v-if="sort % $parent.cols == 0"></div>
    <template v-if="sort == $parent.count">
        <div class="mb-0 mb-sm-1 col-12 col-sm" v-for="n in clearCells" :key="n"></div>
        <div class="mb-1 col-12 col-sm">
            <template v-if="$parent.count != $parent.cols">
                <label><br/></label><br/>
            </template>
            <div @click="$parent.clear" class="btn btn-outline-info btn-block"><i class="fa fas fa-filter mr-1" style="margin-top:-5px"></i> Clear Filters</div>
        </div>
    </template>
</template>

<script>
import {debounce as _debounce} from 'lodash'
import {jQuery as $} from '../../js/common';
let select2Options = {};

export default {
    name: 'DtFilter',
    mounted: function(){
        if(this.default)
            this.$parent.defaults[this.name] = this.input = this.default;
        this.$nextTick(function(){
            var component = this;
            this.$parent.filters[this.name] = this.input;
            this.sort = Object.keys(this.$parent.filters).length;
            if(this.type == 'select2'){
                var select = $('#' + this.cssId);
                if(this.multiple)
                    select2Options.placeholder = this.label || '';
                select.select2(select2Options).on('change', function(){
                    component.filter(component.name, select.val())
                });
            } else if(this.type == 'date'){
                var opens = (this.options !== undefined)? (this.options.opens || 'right') : 'right';
                $('#' + this.cssId).daterangepicker({
                    showDropdowns: true,
                    autoUpdateInput: false,
                    opens: opens,
                    locale: {
                        cancelLabel: 'Clear'
                    }
                }).on('apply.daterangepicker', function(e, picker){
                    var startDate = picker.startDate.format('YYYY-MM-DD');
                    var endDate = picker.endDate.format('YYYY-MM-DD');
                    $(this).val((startDate == endDate)? startDate : (startDate + ' to ' + endDate));
                    component.filter(component.name, $(this).val())
                }).on('cancel.daterangepicker', function(){
                    $(this).val('');
                    component.filter(component.name, $(this).val())
                });
            }

            this.$root.emitter.on('clear', function(e){
                if(component.$parent.datatableRef != e.ref)
                    return;
                component.clear();
            });
        });
    },
    props: {
        name: {
            type: String,
            required: true
        },
        label: {
            type: String,
            required: true
        },
        type: {
            type: String,
            required: true
        },
        multiple: {
            type: Boolean,
            default: false
        },
        values: {
            type: Object
        },
        options: {
            type: Object
        },
        'default': String
    },
    data: function(){
        return {
            input: '',
            sort: 0
        };
    },
    computed: {
        cssId: function(){
            return this.name.replace('.', '-') + '-filter-' + this.type;
        },
        clearCells: function(){
            var count = this.$parent.count + 1,
                setColumns = this.$parent.cols;
            var remainder = count % setColumns;
            return (count != setColumns && remainder != 0)? setColumns - remainder : 0;
        }
    },
    methods: {
        debouncedFilter: _debounce(function(field, value){
            this.filter(field, value);
        }, 200),
        filter: function(field, value){
            this.$parent.filter(field, value);
        },
        clear: function(){
            var element = $('#' + this.cssId), value = this.input = this.default || '';
            if(this.type == 'date' || this.type == 'select2')
                element.val(value);
            if(this.type == 'select2')
                element.select2('destroy').select2(select2Options);
            this.$parent.filters[this.name] = '';
        }
    }
}
</script>