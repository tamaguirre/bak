<template>
    <teleport to="body">
        <div
            class="modal fade"
            tabindex="-1"
            :id="id"
            ref="modalEl"
            aria-hidden="true"
        >
            <div class="modal-dialog" :class="dialogClass">
                <div class="modal-content">
                    <div class="modal-header" v-if="showHeader">
                        <h1 class="modal-title fs-5">
                            <slot name="title">{{ title }}</slot>
                        </h1>
                        <button type="button" class="btn-close" @click="onCancel"></button>
                    </div>

                    <div class="modal-body" v-if="!isQuestion">
                        <slot />
                    </div>

                    <div class="modal-footer" v-if="showFooter">
                        <slot name="footer">
                            <button type="button" class="btn btn-secondary" @click="onCancel">
                                {{ cancelText }}
                            </button>
                            <button
                                type="button"
                                class="btn btn-primary text-white"
                                @click="onOk"
                                :disabled="okDisabled">
                                {{ okText }}
                            </button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </teleport>
</template>

<script setup>
import { ref, watch, onMounted, onBeforeUnmount, computed } from 'vue'
import { Modal } from 'bootstrap'

const props = defineProps({
    modelValue: { type: Boolean, default: false },
    id: { type: String, default: 'ui-modal' },
    title: { type: String, default: '' },
    size: { type: String, default: '' }, // '', 'sm', 'lg', 'xl'
    centered: { type: Boolean, default: true },
    scrollable: { type: Boolean, default: false },
    staticBackdrop: { type: Boolean, default: false },
    showHeader: { type: Boolean, default: true },
    showFooter: { type: Boolean, default: true },
    okText: { type: String, default: 'OK' },
    cancelText: { type: String, default: 'Cancelar' },
    okDisabled: { type: Boolean, default: false },
    isQuestion: { type: Boolean, default: false },

    autoHideOnOk: { type: Boolean, default: false },
    autoHideOnCancel: { type: Boolean, default: true },
})

const emit = defineEmits(['update:modelValue', 'ok', 'cancel', 'shown', 'hidden'])

const modalEl = ref(null)
let instance = null

const dialogClass = computed(() => [
    props.size && `modal-${props.size}`,
    props.centered && 'modal-dialog-centered',
    props.scrollable && 'modal-dialog-scrollable',
].filter(Boolean).join(' '))

const show = () => instance?.show()
const hide = () => instance?.hide()

const onOk = () => {
    emit('ok', { close: hide })
    if (props.autoHideOnOk) hide()
}
const onCancel = () => {
    emit('cancel', { close: hide })
    if (props.autoHideOnCancel) hide()
}

onMounted(() => {
    instance = new Modal(modalEl.value, {
        backdrop: props.staticBackdrop ? 'static' : true,
        keyboard: !props.staticBackdrop,
    })
    modalEl.value.addEventListener('shown.bs.modal', () => emit('shown'))
    modalEl.value.addEventListener('hidden.bs.modal', () => {
        emit('hidden')
        emit('update:modelValue', false)
    })
    if (props.modelValue) show()
})

onBeforeUnmount(() => {
    instance?.dispose?.()
    instance = null
})

watch(() => props.modelValue, (val) => (val ? show() : hide()))

defineExpose({ show, hide })
</script>
