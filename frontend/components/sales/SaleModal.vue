<template>
  <div>
    <Dialog
        :visible="isOpen"
        :header="sale ? 'Modifier une Vente' : 'Ajouter une Vente'"
        modal
        :style="{ width: '40rem' }"
        closable
        @hide="close"
    >
      <div class="p-fluid grid gap-4">
        <!-- Client -->
        <div class="col-12 md:col-6">
          <label for="customer" class="block text-sm font-medium">Client</label>
          <Dropdown
              id="customer"
              v-model="customer"
              :options="customers"
              optionLabel="fullname"
              optionValue="id"
              placeholder="Choisir un client"
              class="w-full"
          />
        </div>

        <!-- Service -->
        <div class="col-12 md:col-6">
          <label for="service" class="block text-sm font-medium">Service</label>
          <Dropdown
              id="service"
              v-model="service"
              :options="services"
              optionLabel="name"
              optionValue="id"
              placeholder="Choisir un service"
              class="w-full"
          />
        </div>

        <!-- Total -->
        <div class="col-12 md:col-6">
          <label for="total" class="block text-sm font-medium">Total (€)</label>
          <InputNumber
              id="total"
              v-model="total"
              mode="decimal"
              :min="0"
              placeholder="Montant total"
              class="w-full"
          />
        </div>

        <!-- Discount -->
        <div class="col-12 md:col-6">
          <label for="discount" class="block text-sm font-medium">Réduction (€) (Optionnel)</label>
          <InputNumber
              id="discount"
              v-model="discount"
              mode="decimal"
              :min="0"
              placeholder="Réduction appliquée"
              class="w-full"
          />
        </div>

        <!-- Status -->
        <div class="col-12 md:col-6">
          <label for="status" class="block text-sm font-medium">Statut</label>
          <Dropdown
              id="status"
              v-model="status"
              :options="statuses"
              optionLabel="label"
              optionValue="value"
              placeholder="Sélectionnez un statut"
              class="w-full"
          />
        </div>

        <!-- Commentaire -->
        <div class="col-12">
          <label for="comment" class="block text-sm font-medium">Commentaire (Optionnel)</label>
          <Textarea
              id="comment"
              v-model="comment"
              rows="3"
              autoResize
              placeholder="Ajouter un commentaire (optionnel)"
              class="w-full"
          />
        </div>
      </div>

      <!-- Footer -->
      <template #footer>
        <div class="flex justify-end gap-4 mt-4">
          <Button label="Annuler" text @click="close" />
          <Button
              :label="sale ? 'Modifier' : 'Ajouter'"
              class="p-button-primary"
              @click="handleSubmit"
          />
        </div>
      </template>
    </Dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { useCustomerStore } from '~/stores/customers';
import { useServiceStore } from '~/stores/services';
import { type Sale, type SaleStatus, SaleStatuses } from '~/interfaces/SaleInterface';
import type { Service } from '~/interfaces/ServiceInterface';

const props = defineProps<{
  isOpen: boolean;
  sale?: Sale | null;
}>();
const emit = defineEmits(['close', 'add', 'update']);

// Stores
const customerStore = useCustomerStore();
const serviceStore = useServiceStore();

// Références
const customers = ref<Customer[]>([]);
const services = ref<Service[]>([]);
const statuses = Object.keys(SaleStatuses).map((key) => ({
  label: SaleStatuses[key as SaleStatus],
  value: key,
}));
const customer = ref<string | null>(null);
const service = ref<string | null>(null);
const status = ref<SaleStatus | null>(null);
const total = ref<number | null>(null);
const discount = ref<number | null>(null);
const comment = ref<string | null>(null);

const resetForm = () => {
  customer.value = null;
  service.value = null;
  total.value = null;
  discount.value = null;
  status.value = null;
  comment.value = null;
};

const close = () => {
  if (!props.sale) {
    resetForm();
  }
  emit('close');
};

const handleSubmit = () => {
  const saleData = {
    customer: customer.value,
    service: service.value,
    total: total.value,
    discount: discount.value,
    status: status.value,
    comment: comment.value,
  };

  props.sale ? emit('update', { ...props.sale, ...saleData }) : emit('add', saleData);
  resetForm();
};

onMounted(() => {
  customerStore.fetchCustomers().then(() => {
    customers.value = customerStore.allCustomers.map((cust) => ({
      ...cust,
      fullname: `${cust.firstname} ${cust.lastname}`,
    }));
  });
  serviceStore.fetchServices().then(() => {
    services.value = serviceStore.allServices;
  });
});

watch(
    () => props.sale,
    (newSale) => {
      if (newSale) {
        customer.value = newSale.customer.id;
        service.value = newSale.service.id;
        total.value = newSale.total;
        discount.value = newSale.discount || null;
        status.value = newSale.status || null;
        comment.value = newSale.comment || null;
      } else {
        resetForm();
      }
    },
    { immediate: true }
);
</script>
