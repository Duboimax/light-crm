interface Customer {
    id: number
    firstname: string
    lastname: string
    email: string
    phone: string,
    addresses: Adress[],
    createdAt: Date
}