<template>
  <PanelItem :index="index" :field="field">
    <template #value>
      <div
        v-if="hasValue"
        :class="`text-${field.textAlign}`"
        class="leading-normal"
      >
        <component
          v-for="button in field.buttons"
          :key="button.value"
          :class="button.classes"
          :is="`index-${button.component}`"
          :field="button"
          :resourceName="resourceName"
        />
      </div>
      <p v-else>&mdash;</p>
    </template>
  </PanelItem>
</template>

<script>
export default {
  props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],

  computed: {
    /**
     * Determine if the field has a value other than null.
     */
    hasValue() {
      return this.field.buttons
    },
  },
}
</script>
