import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from "@/components/ui/select";
import { Checkbox } from "@/components/ui/checkbox";

export default function PatientForm({ data, setData, errors, processing }: any) {
    return (
        <div className="space-y-4">
            <div>
                <Label htmlFor="name">Name</Label>
                <Input id="name" type="text" value={data.name} onChange={(e) => setData('name', e.target.value)} disabled={processing} />
                {errors.name && <span>{errors.name}</span>}
            </div>
            <div>
                <Label htmlFor="cpf">CPF</Label>
                <Input id="cpf" type="text" value={data.cpf} onChange={(e) => setData('cpf', e.target.value)} disabled={processing} />
                {errors.cpf && <span>{errors.cpf}</span>}
            </div>
            <div>
                <Label htmlFor="birth_date">Birth Date</Label>
                <Input id="birth_date" type="date" value={data.birth_date} onChange={(e) => setData('birth_date', e.target.value)} disabled={processing} />
                {errors.birth_date && <span>{errors.birth_date}</span>}
            </div>
            <div>
                <Label htmlFor="phone">Phone</Label>
                <Input id="phone" type="text" value={data.phone} onChange={(e) => setData('phone', e.target.value)} disabled={processing} />
                {errors.phone && <span>{errors.phone}</span>}
            </div>
            <div>
                <Label htmlFor="email">Email</Label>
                <Input id="email" type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} disabled={processing} />
                {errors.email && <span>{errors.email}</span>}
            </div>
            <div>
                <Label htmlFor="address">Address</Label>
                <Input id="address" type="text" value={data.address} onChange={(e) => setData('address', e.target.value)} disabled={processing} />
                {errors.address && <span>{errors.address}</span>}
            </div>
            <div>
                <Label htmlFor="insurance">Insurance</Label>
                <Select value={data.insurance} onValueChange={(value) => setData('insurance', value)} disabled={processing}>
                    <SelectTrigger>
                        <SelectValue placeholder="Select an insurance" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="Nenhum">Nenhum</SelectItem>
                        <SelectItem value="São Francisco">São Francisco</SelectItem>
                        <SelectItem value="Unimed">Unimed</SelectItem>
                    </SelectContent>
                </Select>
                {errors.insurance && <span>{errors.insurance}</span>}
            </div>
            <div className="flex items-center gap-2">
                <Checkbox id="is_active" checked={data.is_active} onCheckedChange={(checked) => setData('is_active', checked === true)} disabled={processing} />
                <Label htmlFor="is_active">Active</Label>
            </div>
        </div>
    );
}
