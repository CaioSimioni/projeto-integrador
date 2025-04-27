import HeadingSmall from '@/components/heading-small';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Toast, ToastDescription, ToastProvider, ToastTitle, ToastViewport } from '@/components/ui/toaster';
import AppLayout from '@/layouts/app-layout';
import InventoryLayout from '@/layouts/inventory-layout';
import { type BreadcrumbItem, type Material } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import { CirclePlus, Pencil, Trash2 } from 'lucide-react';
import { PropsWithChildren, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Estoque & Farmácia',
        href: '/inventory/materials',
    },
];

export default function Materials({ materials }: PropsWithChildren<{ materials: Material[] }>) {
    const [toastVisible, setToastVisible] = useState(false);
    const [dialogOpen, setDialogOpen] = useState(false);
    const [editDialogOpen, setEditDialogOpen] = useState(false);
    const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
    const [selectedMaterial, setSelectedMaterial] = useState<Material | null>(null);

    const {
        data,
        setData,
        post,
        patch,
        delete: destroy,
        processing,
        errors,
        reset,
    } = useForm({
        name: '',
        type: '',
        quantity: 0,
        expiration_date: '',
        description: '',
    });

    const openModal = (type: 'create' | 'edit' | 'delete', material: Material | null = null) => {
        setSelectedMaterial(material);
        if (type === 'create') {
            setDialogOpen(true);
        } else if (type === 'edit') {
            setEditDialogOpen(true);
            if (material) {
                setData({
                    name: material.name,
                    type: material.type,
                    quantity: material.quantity,
                    expiration_date: material.expiration_date,
                    description: material.description,
                });
            }
        } else if (type === 'delete') {
            setDeleteDialogOpen(true);
        }
    };

    const handleCreateMaterial = () => {
        post(route('material.create'), {
            onSuccess: () => {
                reset();
                setDialogOpen(false);
                setToastVisible(true);
            },
            onError: () => {
                setToastVisible(true);
            },
        });
    };

    const handleEditMaterial = () => {
        patch(route('material.update', { id: selectedMaterial!.id }), {
            preserveScroll: true,
            onSuccess: () => {
                reset();
                setEditDialogOpen(false);
                setToastVisible(true);
            },
        });
    };

    const handleDeleteMaterial = (materialId: number) => {
        destroy(route('material.destroy', { id: materialId }), {
            onSuccess: () => {
                setDeleteDialogOpen(false);
                setToastVisible(true);
            },
        });
    };

    return (
        <ToastProvider>
            <AppLayout breadcrumbs={breadcrumbs}>
                <Head title="Materiais" />
                <InventoryLayout>
                    <div className="space-y-6">
                        <HeadingSmall 
                            title="Lista de Materiais" 
                            description="Adicionar, editar ou remover materiais" 
                        />

                        {toastVisible && (
                            <Toast className={toastVisible ? 'bg-green-500' : ''} onOpenChange={setToastVisible} open={toastVisible}>
                                <ToastTitle>Sucesso</ToastTitle>
                                <ToastDescription>Ação concluída com sucesso.</ToastDescription>
                            </Toast>
                        )}

                        <Button onClick={() => openModal('create')}>
                            Novo Material <CirclePlus />
                        </Button>

                        {/* Diálogo de Criação */}
                        <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>Novo Material</DialogTitle>
                                    <DialogDescription>Preencha os detalhes abaixo para criar um novo material.</DialogDescription>
                                </DialogHeader>
                                <div className="space-y-4">
                                    <div>
                                        <Label htmlFor="name">Nome</Label>
                                        <Input
                                            id="name"
                                            type="text"
                                            value={data.name}
                                            onChange={(e) => setData('name', e.target.value)}
                                            disabled={processing}
                                        />
                                        {errors.name && <span className="text-red-500">{errors.name}</span>}
                                    </div>
                                    <div>
                                        <Label htmlFor="type">Tipo</Label>
                                        <Input
                                            id="type"
                                            type="text"
                                            value={data.type}
                                            onChange={(e) => setData('type', e.target.value)}
                                            disabled={processing}
                                        />
                                        {errors.type && <span className="text-red-500">{errors.type}</span>}
                                    </div>
                                    <div>
                                        <Label htmlFor="quantity">Quantidade</Label>
                                        <Input
                                            id="quantity"
                                            type="number"
                                            value={data.quantity}
                                            onChange={(e) => setData('quantity', parseInt(e.target.value, 10))}
                                            disabled={processing}
                                        />
                                        {errors.quantity && <span className="text-red-500">{errors.quantity}</span>}
                                    </div>
                                    <div>
                                        <Label htmlFor="expiration_date">Data de Validade</Label>
                                        <Input
                                            id="expiration_date"
                                            type="date"
                                            value={data.expiration_date}
                                            onChange={(e) => setData('expiration_date', e.target.value)}
                                            disabled={processing}
                                        />
                                        {errors.expiration_date && <span className="text-red-500">{errors.expiration_date}</span>}
                                    </div>
                                    <div>
                                        <Label htmlFor="description">Descrição</Label>
                                        <Input
                                            id="description"
                                            type="text"
                                            value={data.description}
                                            onChange={(e) => setData('description', e.target.value)}
                                            disabled={processing}
                                        />
                                        {errors.description && <span className="text-red-500">{errors.description}</span>}
                                    </div>
                                </div>
                                <DialogFooter>
                                    <Button onClick={handleCreateMaterial} disabled={processing}>
                                        {processing ? 'Criando...' : 'Criar'}
                                    </Button>
                                    <DialogClose asChild>
                                        <Button variant="outline">Cancelar</Button>
                                    </DialogClose>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>

                        {/* Diálogo de Edição */}
                        <Dialog open={editDialogOpen} onOpenChange={setEditDialogOpen}>
                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>Editar Material</DialogTitle>
                                    <DialogDescription>Atualize os detalhes abaixo para editar o material.</DialogDescription>
                                </DialogHeader>
                                <div className="space-y-4">
                                    <div>
                                        <Label htmlFor="name">Nome</Label>
                                        <Input
                                            id="name"
                                            type="text"
                                            value={data.name}
                                            onChange={(e) => setData('name', e.target.value)}
                                            disabled={processing}
                                        />
                                        {errors.name && <span className="text-red-500">{errors.name}</span>}
                                    </div>
                                    <div>
                                        <Label htmlFor="type">Tipo</Label>
                                        <Input
                                            id="type"
                                            type="text"
                                            value={data.type}
                                            onChange={(e) => setData('type', e.target.value)}
                                            disabled={processing}
                                        />
                                        {errors.type && <span className="text-red-500">{errors.type}</span>}
                                    </div>
                                    <div>
                                        <Label htmlFor="quantity">Quantidade</Label>
                                        <Input
                                            id="quantity"
                                            type="number"
                                            value={data.quantity}
                                            onChange={(e) => setData('quantity', parseInt(e.target.value, 10))}
                                            disabled={processing}
                                        />
                                        {errors.quantity && <span className="text-red-500">{errors.quantity}</span>}
                                    </div>
                                    <div>
                                        <Label htmlFor="expiration_date">Data de Validade</Label>
                                        <Input
                                            id="expiration_date"
                                            type="date"
                                            value={data.expiration_date}
                                            onChange={(e) => setData('expiration_date', e.target.value)}
                                            disabled={processing}
                                        />
                                        {errors.expiration_date && <span className="text-red-500">{errors.expiration_date}</span>}
                                    </div>
                                    <div>
                                        <Label htmlFor="description">Descrição</Label>
                                        <Input
                                            id="description"
                                            type="text"
                                            value={data.description}
                                            onChange={(e) => setData('description', e.target.value)}
                                            disabled={processing}
                                        />
                                        {errors.description && <span className="text-red-500">{errors.description}</span>}
                                    </div>
                                </div>
                                <DialogFooter>
                                    <Button onClick={handleEditMaterial} disabled={processing}>
                                        {processing ? 'Atualizando...' : 'Atualizar'}
                                    </Button>
                                    <DialogClose asChild>
                                        <Button variant="outline">Cancelar</Button>
                                    </DialogClose>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>

                        {/* Diálogo de Exclusão */}
                        <Dialog open={deleteDialogOpen} onOpenChange={setDeleteDialogOpen}>
                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>Confirmar Exclusão</DialogTitle>
                                    <DialogDescription>
                                        Tem certeza que deseja excluir o material <strong>{selectedMaterial?.name}</strong>?
                                    </DialogDescription>
                                </DialogHeader>
                                <DialogFooter>
                                    <Button variant="destructive" onClick={() => handleDeleteMaterial(selectedMaterial!.id)}>
                                        Excluir
                                    </Button>
                                    <DialogClose asChild>
                                        <Button variant="outline">Cancelar</Button>
                                    </DialogClose>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>

                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Material</TableHead>
                                    <TableHead>Descrição</TableHead>
                                    <TableHead>Quantidade</TableHead>
                                    <TableHead>Ações</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {materials.map((material) => (
                                    <TableRow key={material.id}>
                                        <TableCell>{material.name}</TableCell>
                                        <TableCell>{material.description}</TableCell>
                                        <TableCell>{material.quantity}</TableCell>
                                        <TableCell className="flex">
                                            <Button variant="outline" className="mr-2" size="sm" onClick={() => openModal('edit', material)}>
                                                Editar
                                                <Pencil className="ml-2 h-4 w-4" />
                                            </Button>
                                            <Button variant="destructive" size="sm" onClick={() => openModal('delete', material)}>
                                                Excluir <Trash2 className="ml-2 h-4 w-4" />
                                            </Button>
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    </div>
                </InventoryLayout>
            </AppLayout>
            <ToastViewport />
        </ToastProvider>
    );
}