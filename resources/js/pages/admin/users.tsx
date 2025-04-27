import HeadingSmall from '@/components/heading-small';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Toast, ToastDescription, ToastProvider, ToastTitle, ToastViewport } from '@/components/ui/toaster';
import AppLayout from '@/layouts/app-layout';
import BasicLayout from '@/layouts/basic-layout';
import { type BreadcrumbItem, type NavItem, type User } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import { CirclePlus, Pencil, Trash2 } from 'lucide-react';
import { type PropsWithChildren, useEffect, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Painel Administrativo',
        href: '/admin',
    },
];

const sidebarNavItems: NavItem[] = [
    {
        title: 'Geral',
        href: '/admin',
        icon: null,
    },
    {
        title: 'Usuários',
        href: '/admin/users',
        icon: null,
    },
];

export default function Users({ users, roles }: PropsWithChildren<{ users: User[]; roles: { [key: string]: string } }>) {
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
        email: '',
        password: '' as string | undefined,
        password_confirmation: '' as string | undefined,
        role: 'adm',
    });

    const [dialogOpen, setDialogOpen] = useState(false);
    const [editDialogOpen, setEditDialogOpen] = useState(false);
    const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
    const [selectedUser, setSelectedUser] = useState<User | null>(null);
    const [toastVisible, setToastVisible] = useState(false);
    const [resetPassword, setResetPassword] = useState(false);

    const openModal = (type: 'create' | 'edit' | 'delete', user: User | null = null) => {
        setSelectedUser(user);
        if (type === 'create') {
            setDialogOpen(true);
        } else if (type === 'edit') {
            setEditDialogOpen(true);
            if (user) {
                setData({
                    name: user.name,
                    email: user.email,
                    role: user.role,
                    password: '',
                    password_confirmation: '',
                });
            }
        } else if (type === 'delete') {
            setDeleteDialogOpen(true);
        }
    };

    const handleCreateUser = () => {
        post(route('user.create'), {
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

    const handleEditUser = () => {
        if (!resetPassword) {
            delete data.password;
            delete data.password_confirmation;
        }

        patch(route('user.update', { id: selectedUser!.id }), {
            preserveScroll: true,
            onSuccess: () => {
                reset();
                setEditDialogOpen(false);
                setToastVisible(true);
            },
        });
    };

    const handleDeleteUser = (userId: number) => {
        destroy(route('user.destroy', { id: userId }), {
            onSuccess: () => {
                setDeleteDialogOpen(false);
                setToastVisible(true);
            },
        });
    };

    useEffect(() => {
        if (selectedUser) {
            setData({
                name: selectedUser.name,
                email: selectedUser.email,
                role: selectedUser.role,
                password: resetPassword ? data.password : '',
                password_confirmation: resetPassword ? data.password_confirmation : '',
            });
        }
    }, [selectedUser, resetPassword]);

    return (
        <ToastProvider>
            <AppLayout breadcrumbs={breadcrumbs}>
                <Head title="Administração" />
                <BasicLayout sidebarNavItems={sidebarNavItems}>
                    <div className="space-y-6">
                        <HeadingSmall title="Informações de Usuários" description="Crie, atualize ou remova usuários do sistema" />

                        {toastVisible && (
                            <Toast className={toastVisible ? 'bg-green-500' : ''} onOpenChange={setToastVisible} open={toastVisible}>
                                <ToastTitle>Sucesso</ToastTitle>
                                <ToastDescription>Ação concluída com sucesso.</ToastDescription>
                            </Toast>
                        )}

                        <Button onClick={() => setDialogOpen(true)}>
                            Novo Usuário <CirclePlus />
                        </Button>

                        <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>Criar Novo Usuário</DialogTitle>
                                    <DialogDescription>Preencha os detalhes abaixo para criar um novo usuário.</DialogDescription>
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
                                        {errors.name && <span>{errors.name}</span>}
                                    </div>
                                    <div>
                                        <Label htmlFor="email">E-mail</Label>
                                        <Input
                                            id="email"
                                            type="email"
                                            value={data.email}
                                            onChange={(e) => setData('email', e.target.value)}
                                            disabled={processing}
                                        />
                                        {errors.email && <span>{errors.email}</span>}
                                    </div>
                                    <div>
                                        <Label htmlFor="password">Senha</Label>
                                        <Input
                                            id="password"
                                            type="password"
                                            value={data.password}
                                            onChange={(e) => setData('password', e.target.value)}
                                            disabled={processing}
                                        />
                                        {errors.password && <span>{errors.password}</span>}
                                    </div>
                                    <div>
                                        <Label htmlFor="password_confirmation">Confirmar Senha</Label>
                                        <Input
                                            id="password_confirmation"
                                            type="password"
                                            value={data.password_confirmation}
                                            onChange={(e) => setData('password_confirmation', e.target.value)}
                                            disabled={processing}
                                        />
                                        {errors.password_confirmation && <span>{errors.password_confirmation}</span>}
                                    </div>
                                    <div>
                                        <Label htmlFor="role">Função</Label>
                                        <Select value={data.role} onValueChange={(value) => setData('role', value)} disabled={processing}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Selecione uma função" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                {Object.keys(roles).map((role) => (
                                                    <SelectItem key={role} value={role}>
                                                        {roles[role]}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                        {errors.role && <span>{errors.role}</span>}
                                    </div>
                                </div>
                                <DialogFooter>
                                    <Button onClick={handleCreateUser} disabled={processing}>
                                        {processing ? 'Criando...' : 'Criar'}
                                    </Button>
                                    <DialogClose asChild>
                                        <Button variant="outline">Cancelar</Button>
                                    </DialogClose>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>

                        <Dialog open={editDialogOpen} onOpenChange={setEditDialogOpen}>
                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>Editar Usuário</DialogTitle>
                                    <DialogDescription>Atualize os detalhes abaixo para editar o usuário.</DialogDescription>
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
                                        {errors.name && <span>{errors.name}</span>}
                                    </div>
                                    <div>
                                        <Label htmlFor="email">E-mail</Label>
                                        <Input
                                            id="email"
                                            type="email"
                                            value={data.email}
                                            onChange={(e) => setData('email', e.target.value)}
                                            disabled={processing}
                                        />
                                        {errors.email && <span>{errors.email}</span>}
                                    </div>
                                    <div className="flex items-center gap-2">
                                        <Checkbox
                                            id="reset_password"
                                            checked={resetPassword}
                                            onCheckedChange={(checked) => setResetPassword(checked === true)}
                                            disabled={processing}
                                        />
                                        <Label htmlFor="reset_password">Redefinir Senha</Label>
                                    </div>
                                    {resetPassword && (
                                        <>
                                            <div>
                                                <Label htmlFor="password">Senha</Label>
                                                <Input
                                                    id="password"
                                                    type="password"
                                                    value={data.password}
                                                    onChange={(e) => setData('password', e.target.value)}
                                                    disabled={processing}
                                                />
                                                {errors.password && <span>{errors.password}</span>}
                                            </div>
                                            <div>
                                                <Label htmlFor="password_confirmation">Confirmar Senha</Label>
                                                <Input
                                                    id="password_confirmation"
                                                    type="password"
                                                    value={data.password_confirmation}
                                                    onChange={(e) => setData('password_confirmation', e.target.value)}
                                                    disabled={processing}
                                                />
                                                {errors.password_confirmation && <span>{errors.password_confirmation}</span>}
                                            </div>
                                        </>
                                    )}
                                    <div>
                                        <Label htmlFor="role">Função</Label>
                                        <Select value={data.role} onValueChange={(value) => setData('role', value)} disabled={processing}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Selecione uma função" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                {Object.keys(roles).map((role) => (
                                                    <SelectItem key={role} value={role}>
                                                        {roles[role]}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                        {errors.role && <span>{errors.role}</span>}
                                    </div>
                                </div>
                                <DialogFooter>
                                    <Button onClick={handleEditUser} disabled={processing}>
                                        {processing ? 'Atualizando...' : 'Atualizar'}
                                    </Button>
                                    <DialogClose asChild>
                                        <Button variant="outline">Cancelar</Button>
                                    </DialogClose>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>

                        <Dialog open={deleteDialogOpen} onOpenChange={setDeleteDialogOpen}>
                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>Confirmar Exclusão</DialogTitle>
                                    <DialogDescription>
                                        Tem certeza que deseja excluir o usuário <strong>{selectedUser?.name}</strong>?
                                    </DialogDescription>
                                </DialogHeader>
                                <DialogFooter>
                                    <Button variant="destructive" onClick={() => handleDeleteUser(selectedUser!.id)}>
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
                                    <TableHead>ID</TableHead>
                                    <TableHead className="text-center">Nome</TableHead>
                                    <TableHead className="text-center">E-mail</TableHead>
                                    <TableHead className="text-center">Função</TableHead>
                                    <TableHead className="text-center">Criado em</TableHead>
                                    <TableHead className="text-center">Atualizado em</TableHead>
                                    <TableHead className="text-center">Ações</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {users.map((user) => (
                                    <TableRow key={user.id}>
                                        <TableCell className="px-6 py-4 whitespace-nowrap">{user.id}</TableCell>
                                        <TableCell className="px-6 py-4 whitespace-nowrap">{user.name}</TableCell>
                                        <TableCell className="px-6 py-4 whitespace-nowrap">{user.email}</TableCell>
                                        <TableCell className="px-6 py-4 whitespace-nowrap">{roles[user.role]}</TableCell>
                                        <TableCell className="px-6 py-4 whitespace-nowrap">
                                            {new Date(user.created_at).toLocaleString('pt-BR')}
                                        </TableCell>
                                        <TableCell className="px-6 py-4 whitespace-nowrap">
                                            {new Date(user.updated_at).toLocaleString('pt-BR')}
                                        </TableCell>
                                        <TableCell className="px-6 py-4 whitespace-nowrap">
                                            <Button variant="outline" className="mr-2" size="sm" onClick={() => openModal('edit', user)}>
                                                Editar
                                                <Pencil />
                                            </Button>
                                            <Button variant="destructive" size="sm" onClick={() => openModal('delete', user)}>
                                                Excluir <Trash2 />
                                            </Button>
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    </div>
                </BasicLayout>
            </AppLayout>
            <ToastViewport />
        </ToastProvider>
    );
}
