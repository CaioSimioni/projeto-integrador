import { type BreadcrumbItem } from '@/types';

import { Card, CardContent, CardHeader } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import InventoryLayout from '@/layouts/inventory-layout';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Inventory & Pharmacy',
        href: '/inventory',
    },
];

export default function InventoryDashboard({ materialsQuantity }: { materialsQuantity: number }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Patients" />
            <InventoryLayout>
                <div className="grid grid-cols-1 gap-6 p-6 sm:grid-cols-1 lg:grid-cols-2">
                    <Card>
                        <CardHeader>Available Materials</CardHeader>
                        <CardContent>
                            <h2 className="text-base font-bold text-gray-900 dark:text-white">{materialsQuantity}</h2>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader>Available Medicines</CardHeader>
                        <CardContent>
                            <h2 className="text-base font-bold text-gray-900 dark:text-white">{0}</h2>
                        </CardContent>
                    </Card>
                </div>
            </InventoryLayout>
        </AppLayout>
    );
}
