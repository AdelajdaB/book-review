export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
}

export interface Book {
    id: number;
    title: string;
    author: string;
    description: string;
    rating: float;
}

export interface Paginated<T> {
  data: T[]
  current_page: number
  from: number | null
  last_page: number
  links: {
    url: string | null
    label: string
    active: boolean
  }[]
  per_page: number
  to: number | null
  total: number
}

export interface PaginationLink {
  url: string | null
  label: string
  active: boolean
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};
