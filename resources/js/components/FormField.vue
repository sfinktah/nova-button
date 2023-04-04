<template>
  <FieldWrapper :stacked="field.stacked" v-if="field.visible">
    <div
      class="px-6 md:px-8 mt-2 md:mt-0"
      :class="field.stacked ? 'md:pt-2 w-full' : 'w-full md:w-1/5 md:py-5'"
    >
      <FormLabel
        :label-for="labelFor || field.uniqueKey"
        :class="{ 'mb-2': shouldShowHelpText }"
      >
        {{ field.label }}
      </FormLabel>
    </div>
    <div
      class="mt-1 md:mt-0 pb-5 px-6 md:px-8"
      :class="{
        'md:w-full': fullWidthContent,
        'md:w-4/5': !fullWidthContent,
        'w-full md:py-5': !field.stacked,
        'w-full md:pt-2': field.stacked,
      }"
    >
      <div class="nova-button-align">
        <span v-if="field.confirm == null">
          <nova-button
            :field="field"
            :resourceName="resourceName"
            :resourceId="resourceId"
            :disabled="field.disabled"
            @finished="reload"
          />
        </span>
        <div v-else>
          <button
            :class="field.classes"
            :disabled="field.disabled"
            v-html="field.text"
            @click.prevent.stop="modalIsOpen = true"
            type="button"
          />

          <confirm-modal
            v-if="modalIsOpen"
            :field="field"
            :resource="resource"
            :resource-name="resourceName"
            @finished="modalReload"
            @closed="modalIsOpen = false"
          />
        </div>
      </div>
      <HelpText
        class="help-text mt-2"
        v-if="shouldShowHelpText"
        v-html="field.helpText"
      />
    </div>
  </FieldWrapper>
</template>

<script>
import field from '../../field';
import NovaButton from './NovaButton';
import ConfirmModal from './ConfirmModal';
import { DependentFormField, mapProps } from '@/mixins'
import FieldWrapper from "@/components/FieldWrapper.vue";
import HelpText from "@/components/HelpText.vue";
import FormLabel from "@/components/FormLabel.vue";


export default {
  components: { NovaButton, ConfirmModal },
  props: {
    field: { type: Object, required: true },
    resourceName: { type: String },
    resource: { default: undefined },
    resourceId: { default: undefined },
    labelFor: { default: null },
    index: { default: null },
    ...mapProps(['showHelpText']),
  },
  mixins: [field, DependentFormField],
  computed: {
    /**
     * Determine help text should be shown.
     */
    shouldShowHelpText() {
      return this.showHelpText && this.field.helpText?.length > 0
    },
  },
};
</script>
