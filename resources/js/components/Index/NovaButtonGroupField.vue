<template>
  <div :class="`text-${field.textAlign}`">
    <template v-if="hasValue">
      <div class="leading-normal">
        <component
          :key="button.value"
          v-for="button in field.buttons"
          class="whitespace-nowrap"
          :is="`index-${button.component}`"
          :field="button"
          :resourceName="resourceName"
        />
      </div>
    </template>
    <p v-else>&mdash;</p>
  </div>
</template>

<script>
export default {
  props: ['resourceName', 'field'],

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
