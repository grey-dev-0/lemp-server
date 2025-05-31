<template>
    <div ref="editorContainer" class="monaco-editor-container"></div>
    <input type="hidden" :name="name" :value="code">
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, shallowRef, watch } from 'vue';
import * as monaco from 'monaco-editor';
import type { editor } from 'monaco-editor';
import {registerNginxLanguage} from "./nginx";
import monokai from 'monaco-themes/themes/Monokai.json';

// Import workers using Vite's worker syntax
import EditorWorker from 'monaco-editor/esm/vs/editor/editor.worker?worker';
import JsonWorker from 'monaco-editor/esm/vs/language/json/json.worker?worker';
import CssWorker from 'monaco-editor/esm/vs/language/css/css.worker?worker';
import HtmlWorker from 'monaco-editor/esm/vs/language/html/html.worker?worker';
import TsWorker from 'monaco-editor/esm/vs/language/typescript/ts.worker?worker';

// Configure Monaco workers
self.MonacoEnvironment = {
    getWorker(_, label) {
        if (label === 'json') {
            return new JsonWorker();
        }
        if (label === 'css' || label === 'scss' || label === 'less') {
            return new CssWorker();
        }
        if (label === 'html' || label === 'handlebars' || label === 'razor') {
            return new HtmlWorker();
        }
        if (label === 'typescript' || label === 'javascript') {
            return new TsWorker();
        }
        return new EditorWorker();
    },
};

const props = defineProps({
    name: String,
    value: {
        type: String,
        default: ''
    }
});

const editorContainer = ref<HTMLElement | null>(null);
const editorInstance = shallowRef<editor.IStandaloneCodeEditor | null>(null);
const code = ref(props.value);

const editorOptions: editor.IStandaloneEditorConstructionOptions = {
    value: code.value,
    language: 'nginx',
    theme: 'monokai',
    automaticLayout: true,
    renderLineHighlight: 'all',
    minimap: { enabled: false },
};

onMounted(() => {
    registerNginxLanguage();

    if (editorContainer.value) {
        monaco.editor.defineTheme('monokai', monokai);
        editorInstance.value = monaco.editor.create(editorContainer.value, editorOptions);

        editorInstance.value.onDidChangeModelContent(() => {
            code.value = editorInstance.value!.getValue();
        });

        watch(code, (newValue) => {
            if (editorInstance.value && newValue !== editorInstance.value.getValue()) {
                editorInstance.value.setValue(newValue);
            }
        }, { immediate: true });
    }
});

onBeforeUnmount(() => {
    if (editorInstance.value) {
        editorInstance.value.dispose();
    }
});
</script>

<style scoped>
.monaco-editor-container {
    width: 100%;
    height: 500px;
    border: 1px solid #ccc;
}
</style>