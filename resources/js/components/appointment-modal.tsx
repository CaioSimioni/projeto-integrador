import { Dialog, DialogContent, DialogHeader, DialogFooter, DialogTitle, DialogDescription, DialogClose } from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";

export default function AppointmentModal({
    open,
    setOpen,
    title,
    description,
    children,
    onConfirm,
    confirmText,
    confirmVariant = "default",
    processing,
}: any) {
    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{title}</DialogTitle>
                    <DialogDescription>{description}</DialogDescription>
                </DialogHeader>
                {children}
                <DialogFooter>
                    <Button onClick={onConfirm} disabled={processing} variant={confirmVariant}>
                        {processing ? "Processando..." : confirmText}
                    </Button>
                    <DialogClose asChild>
                        <Button variant="outline">Cancelar</Button>
                    </DialogClose>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
