<script setup lang="ts">
interface Props {
  class?: string;
  rows?: number;
  placeholder?: string;
  name?: string;
  id?: string;
  required?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  rows: 3,
});

defineEmits<{
  input: [value: string];
  change: [value: string];
}>();
</script>

<template>
  <textarea
    :class="[
      'flex min-h-20 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
      props.class
    ]"
    :rows="props.rows"
    :placeholder="props.placeholder"
    :name="props.name"
    :id="props.id"
    :required="props.required"
    @input="$emit('input', ($event.target as HTMLTextAreaElement).value)"
    @change="$emit('change', ($event.target as HTMLTextAreaElement).value)"
  />
</template>