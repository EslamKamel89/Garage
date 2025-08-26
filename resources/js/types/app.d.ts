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
    deleted_at: any;
    created_at: string;
    updated_at: string;
    year_range_formatted: string;
}

export interface YearRange {
    from: number;
    to: number;
}
