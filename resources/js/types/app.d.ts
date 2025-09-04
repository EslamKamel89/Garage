export interface Role {
    id: number;
    name: string;
    name_ar: string;
    guard_name: string;
    created_at: string;
    permissions: Permission[];
}
export interface Permission {
    id: number;
    name: string;
    name_ar: string;
    guard_name: string;
    created_at: string;
    updated_at: string;
}
export interface CarModel {
    id: number;
    name: string;
    year_range: YearRange;
    created_at: string;
    updated_at: string;
    year_range_formatted: string;
}

export interface YearRange {
    from: number;
    to: number;
}

export interface Category {
    id: number;
    name: string;
    description: string;
    created_at: string;
    updated_at: string;
}

export interface Client {
    id: number;
    name: string;
    mobile: string;
    email: string;
    address: string;
    notes: string;
    car_model: CarModel;
    created_at: string;
    updated_at: string;
}
export interface Product {
    id: number;
    category_id: number;
    code: string;
    name: string;
    description: string;
    buy_price: number;
    sell_price: number;
    quantity: number;
    min_stock_quantity: number;
    notes: string;
    created_at: string;
    updated_at: string;
    category: Category;
}
export interface Invoice {
    id: number;
    client_id: number;
    user_id: number;
    status: 'unpaid' | 'paid' | 'partial' | 'cancelled';
    calculated_total: string;
    actual_total: string;
    actual_paid_amount: string;
    labor_info: LaborInfo;
    notes: string;
    deleted_at: any;
    created_at: string;
    updated_at: string;
    client: Client;
    user: User;
}

export interface LaborInfo {
    items: Item[];
}

export interface Item {
    fee: number;
    name: string;
}

export interface Labour {
    id: number;
    name_ar: string;
    name_en: string;
    base_fee: number;
    is_active: boolean;
    deleted_at: any;
    created_at: string;
    updated_at: string;
}

export interface InvoiceItem {
    product_id: number;
    quantity: number;
    unit_price: number;
    total: number;
}
