import type {Service} from "~/interfaces/ServiceInterface";

export type SaleStatus = 'completed' | 'pending' | 'cancelled';

export const SaleStatuses: Record<SaleStatus, string> = {
    completed: 'Complétée',
    pending: 'En attente',
    cancelled: 'Annulée',
};

export interface Sale {
    id: string;
    customer: Customer
    service: Service
    saleDate: string;
    total: number;
    discount: number;
    status: SaleStatus;
}