<script setup lang="ts">
import type { DropdownMenuItemProps } from "reka-ui"
import { type HTMLAttributes, computed } from "vue"
import { reactiveOmit } from "@vueuse/core"
import { DropdownMenuItem, useForwardProps } from "reka-ui"
import { cn } from "@/lib/utils"

// Aggiungiamo le props per gestire l'azione del form
const props = withDefaults(defineProps<DropdownMenuItemProps & {
  class?: HTMLAttributes["class"]
  inset?: boolean
  variant?: "default" | "destructive" | "warning" | "success"
  action?: string // URL dove inviare il form
  method?: string // POST, DELETE, etc.
  isForm?: boolean // Per decidere se renderizzarlo come form o normale
}>(), {
  variant: "default",
  method: "POST",
  isForm: false
})

const delegatedProps = reactiveOmit(props, "inset", "variant", "class", "action", "method", "isForm")
const forwardedProps = useForwardProps(delegatedProps)
</script>

<template>
  <DropdownMenuItem
    data-slot="dropdown-menu-item"
    :data-inset="inset ? '' : undefined"
    :data-variant="variant"
    v-bind="forwardedProps"
    @select="(e) => isForm ? e.preventDefault() : null"
    :class="cn(
      'focus:bg-accent focus:text-accent-foreground data-[variant=destructive]:text-destructive data-[variant=destructive]:focus:bg-destructive/10 dark:data-[variant=destructive]:focus:bg-destructive/20 data-[variant=destructive]:focus:text-destructive data-[variant=destructive]:*:[svg]:!text-destructive [&_svg:not([class*=\'text-\'])]:text-muted-foreground relative flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-hidden select-none data-[disabled]:pointer-events-none data-[disabled]:opacity-50 data-[inset]:pl-8 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4', 
      'focus:bg-accent focus:text-accent-foreground data-[variant=success]:text-emerald-600 data-[variant=success]:focus:bg-emerald-600/10 dark:data-[variant=success]:focus:bg-emerald-600/20 data-[variant=success]:focus:text-emerald-600 data-[variant=success]:*:[svg]:!text-emerald-600',
      'focus:bg-accent focus:text-accent-foreground data-[variant=warning]:text-orange-600 data-[variant=warning]:focus:bg-orange-600/10 dark:data-[variant=warning]:focus:bg-orange-600/20 data-[variant=warning]:focus:text-orange-600 data-[variant=warning]:*:[svg]:!text-orange-600',
      props.class,
      isForm && 'p-0' // Rimuoviamo il padding se è un form per farlo riempire dal bottone
    )"
  >
    <template v-if="isForm">
      <form :action="action" :method="method === 'GET' ? 'GET' : 'POST'" class="w-full">
        <slot name="csrf" />
        <slot name="fields" />
        
        <input v-if="['DELETE', 'PUT', 'PATCH'].includes(method.toUpperCase())" type="hidden" name="_method" :value="method.toUpperCase()">

        <button type="submit" class="w-full px-2 py-1.5 text-left flex items-center gap-2 cursor-pointer">
          <slot />
        </button>
      </form>
    </template>
    
    <template v-else>
      <slot />
    </template>
  </DropdownMenuItem>
</template>