/**
 * ALERTA!!! Não mexa nesta bomba.
 *
 * ALERT!!! Do not mess with this bomb.
 */

import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { useEffect, useRef } from 'react';

interface MapModalProps {
    open: boolean;
    onClose: () => void;
    endereco: string;
}

type GeocodeResult = {
    items: Array<{
        position: {
            lat: number;
            lng: number;
        };
    }>;
};

/**
 * Componente `MapModal` que exibe um modal com um mapa interativo utilizando a API HERE Maps.
 *
 * @param {MapModalProps} props - Propriedades do componente.
 * @param {boolean} props.open - Indica se o modal está aberto.
 * @param {() => void} props.onClose - Função chamada ao fechar o modal.
 * @param {string} props.endereco - Endereço a ser geocodificado e exibido no mapa.
 *
 * @returns {JSX.Element} O componente de modal contendo o mapa.
 *
 * @remarks
 * Este componente utiliza a API HERE Maps para renderizar um mapa interativo e geocodificar
 * um endereço fornecido. Ele carrega dinamicamente os scripts necessários para a API e
 * inicializa o mapa com as configurações padrão. Caso o endereço seja válido, o mapa é
 * centralizado no local correspondente e um marcador é adicionado.
 *
 * @example
 * ```tsx
 * <MapModal
 *   open={isModalOpen}
 *   onClose={() => setModalOpen(false)}
 *   endereco="Praça dos Três Poderes, Brasília, DF"
 * />
 * ```
 *
 * @component
 *
 * @hook
 * - `useRef`: Utilizado para referenciar o container do mapa e a instância do mapa.
 * - `useEffect`: Utilizado para carregar os scripts da API HERE Maps e inicializar o mapa
 *   quando o modal é aberto.
 *
 * @dependencies
 * - HERE Maps API: Carregada dinamicamente através de scripts externos.
 * - `Dialog`, `DialogContent`, `DialogHeader`, `DialogTitle`, `DialogDescription`: Componentes
 *   de UI utilizados para estruturar o modal.
 *
 * @note
 * - Certifique-se de definir a variável de ambiente `VITE_HERE_API_KEY` com a chave da API HERE Maps.
 * - O componente utiliza coordenadas de fallback (Brasília) caso o endereço não seja válido.
 * - O zoom padrão é ajustado para 16, mas pode ser alterado conforme necessário.
 *
 * @error
 * - Caso ocorra um erro ao carregar os scripts ou inicializar o mapa, ele será registrado no console.
 * - Erros de geocodificação também são tratados e registrados no console.
 */
export function MapModal({ open, onClose, endereco }: MapModalProps) {
    const mapRef = useRef<HTMLDivElement>(null);

    // Unexpected any. Specify a different type.eslint@typescript-eslint/no-explicit-any
    // Tentei resolver e quebrou o código
    const mapInstance = useRef<any>(null);

    useEffect(() => {
        const loadScripts = async () => {
            try {
                // Carrega core primeiro
                if (!window.H) {
                    await new Promise<void>((resolve) => {
                        const coreScript = document.createElement('script');
                        coreScript.src = 'https://js.api.here.com/v3/3.1/mapsjs-core.js';
                        coreScript.onload = () => {
                            resolve();
                        };
                        document.body.appendChild(coreScript);
                    });
                }

                // Carrega service
                if (!window.H?.service) {
                    await new Promise<void>((resolve) => {
                        const serviceScript = document.createElement('script');
                        serviceScript.src = 'https://js.api.here.com/v3/3.1/mapsjs-service.js';
                        serviceScript.onload = () => resolve();
                        document.body.appendChild(serviceScript);
                    });
                }

                // Carrega mapevents
                if (!window.H?.mapevents) {
                    await new Promise<void>((resolve) => {
                        const eventsScript = document.createElement('script');
                        eventsScript.src = 'https://js.api.here.com/v3/3.1/mapsjs-mapevents.js';
                        eventsScript.onload = () => resolve();
                        document.body.appendChild(eventsScript);
                    });
                }

                // Carrega UI
                if (!window.H?.ui) {
                    await new Promise<void>((resolve) => {
                        const uiScript = document.createElement('script');
                        uiScript.src = 'https://js.api.here.com/v3/3.1/mapsjs-ui.js';
                        uiScript.onload = () => resolve();
                        document.body.appendChild(uiScript);
                    });
                }
            } catch (error) {
                console.error('Erro ao carregar scripts:', error);
            }
        };

        const initMap = async () => {
            try {
                const H = window.H;

                const platform = new H.service.Platform({
                    apikey: import.meta.env.VITE_HERE_API_KEY,
                });

                const defaultLayers = platform.createDefaultLayers();

                // Aguarda renderização do container
                await new Promise((resolve) => setTimeout(resolve, 100));

                mapInstance.current = new H.Map(mapRef.current!, defaultLayers.vector.normal.map, {
                    zoom: 16, // Aumenta zoom para precisão
                    center: { lat: -15.795, lng: -47.891 }, // Coordenadas de Brasília como fallback
                    pixelRatio: window.devicePixelRatio || 1,
                });

                // Comportamentos
                // 'behavior' is assigned a value but never used.eslint@typescript-eslint/no-unused-vars
                const behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(mapInstance.current));
                H.ui.UI.createDefault(mapInstance.current, defaultLayers);

                // Geocoding
                const geocoder = platform.getSearchService();
                geocoder.geocode(
                    { q: endereco, additionalData: 'Country2,true' }, // Modo estrito
                    (result: GeocodeResult) => {
                        if (result.items?.length) {
                            const location = result.items[0].position;

                            mapInstance.current.setCenter(location);
                            mapInstance.current.addObject(new H.map.Marker(location));
                            mapInstance.current.setZoom(18); // Zoom máximo
                        }
                    },
                    (error: Error) => {
                        console.error('Erro no geocoding:', error);
                    },
                );
            } catch (error) {
                console.error('Erro na inicialização:', error);
            }
        };

        if (open) {
            loadScripts()
                .then(initMap)
                .catch((error) => console.error('Erro geral:', error));
        }

        return () => {
            if (mapInstance.current) {
                mapInstance.current.dispose();
            }
        };
    }, [open, endereco]);

    return (
        <Dialog open={open} onOpenChange={(isOpen) => !isOpen && onClose()}>
            <DialogContent className="w-[90vw] max-w-2xl">
                <DialogHeader>
                    <DialogTitle>Localização do Paciente</DialogTitle>
                    <DialogDescription>{endereco}</DialogDescription>
                </DialogHeader>
                <div ref={mapRef} className="h-[500px] w-full rounded-lg border-2 bg-gray-50" />
            </DialogContent>
        </Dialog>
    );
}
