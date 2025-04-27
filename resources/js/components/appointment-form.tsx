import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { Patient } from '@/types';

export default function AppointmentForm({ data, setData, errors, processing, patients }: any) {
    return (
        <div className="space-y-4">
            <div>
                <Label htmlFor="patient_id">Paciente</Label>
                <Select value={data.patient_id} onValueChange={(value) => setData('patient_id', value)} disabled={processing}>
                    <SelectTrigger>
                        <SelectValue placeholder="Selecione um paciente" />
                    </SelectTrigger>
                    <SelectContent>
                        {patients.map((patient: Patient) => (
                            <SelectItem key={patient.id} value={String(patient.id)}>
                                {patient.name}
                            </SelectItem>
                        ))}
                    </SelectContent>
                </Select>
                {errors.patient_id && <span className="text-sm text-red-500">{errors.patient_id}</span>}
            </div>

            <div>
                <Label htmlFor="appointment_date">Data do Agendamento</Label>
                <Input
                    id="appointment_date"
                    type="datetime-local"
                    value={data.appointment_date}
                    onChange={(e) => setData('appointment_date', e.target.value)}
                    disabled={processing}
                />
                {errors.appointment_date && <span className="text-sm text-red-500">{errors.appointment_date}</span>}
            </div>

            <div>
                <Label htmlFor="notes">Notas</Label>
                <Textarea id="notes" value={data.notes} onChange={(e) => setData('notes', e.target.value)} disabled={processing} />
                {errors.notes && <span className="text-sm text-red-500">{errors.notes}</span>}
            </div>
        </div>
    );
}
