export interface Service {
    id: string; // UUID généré
    name: string; // Nom du service
    description: string; // Description du service
    hourlyRate: number; // Taux horaire
    duration: number; // Durée en minutes
    owner?: User; // Propriétaire du service (optionnel côté front)
    createdAt?: string; // Date de création (optionnel)
}