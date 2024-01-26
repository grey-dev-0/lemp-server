<template>
    <div class="card">
        <div v-if="!!$slots.toolbar" :class="headerClass">
            <h4 :class="'float-left mb-0' + (whiteTitle? ' text-white' : '')">{{title}}</h4>
            <div class="btn-toolbar float-right">
                <slot name="toolbar"></slot>
            </div>
        </div>
        <h4 v-else :class="headerClass + (whiteTitle? ' text-white' : '')">{{title}}</h4>
        <div class="card-body" v-if="!noPadding">
            <slot></slot>
        </div>
        <template v-else>
            <slot></slot>
        </template>
        <div v-if="!!$slots.footer" class="card-footer">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: "Card",
    props: {
        title: {
            type: String,
            required: true
        },
        noPadding: {
            type: Boolean,
            default: false
        },
        color: String
    },
    computed: {
        headerClass: function(){
            var theClass = 'card-header';
            if(this.color !== undefined){
                theClass += ' bg-' + this.color;
                if(this.whiteTitle)
                    theClass += ' text-white';
            }
            return theClass;
        },
        whiteTitle: function(){
            return (this.color !== undefined && parseInt(this.color.replace(/[^0-9]/g, '')) <= 5);
        }
    }
}
</script>

<style>
.btn-toolbar{
    max-height: 28px;
}
</style>