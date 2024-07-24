<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProviderRepositoryInterface;
use App\Http\Requests\StoreProviderRequest;
use App\Http\Requests\UpdateProviderRequest;
use App\Repositories\Contracts\AddressRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProviderController extends Controller
{
    protected $providerRepository;
    protected $addressRepository;

    public function __construct(
        ProviderRepositoryInterface $providerRepository,
        AddressRepositoryInterface $addressRepository
    )
    {
        $this->providerRepository = $providerRepository;
        $this->addressRepository = $addressRepository;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $providers = $this->providerRepository->paginate($perPage);
        return response()->json($providers);
    }

    public function show($id)
    {
        $provider = $this->providerRepository->find($id);

        if ($provider) {
            $provider->address;
            return response()->json($provider);
        }

        return response()->json(['message' => 'Provider not found'], 404);
    }

    public function store(StoreProviderRequest $request)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            $addressData = $validatedData['address'];
            $address = $this->addressRepository->create($addressData);

            $providerData = $validatedData;
            $providerData['address_id'] = $address->id;
            unset($providerData['address']);
            $provider = $this->providerRepository->create($providerData);

            DB::commit();
            $provider->address;

            return response()->json($provider, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while creating the provider.'], 500);
        }
    }

    public function update(UpdateProviderRequest $request, $id)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            $provider = $this->providerRepository->find($id);

            if (!$provider) {
                return response()->json(['message' => 'Provider not found'], 404);
            }

            if (isset($validatedData['address'])) {
                $addressData = $validatedData['address'];
                $this->addressRepository->update($provider->address_id, $addressData);
            }

            $providerData = $validatedData;
            unset($providerData['address']);
            $this->providerRepository->update($id, $providerData);

            DB::commit();

            return response()->json($validatedData);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while updating the provider.'], 500);
        }
    }

    public function destroy($id)
    {
        $deleted = $this->providerRepository->delete($id);

        if ($deleted) {
            return response()->json(null, 204);
        }

        return response()->json(['message' => 'Provider not found'], 404);
    }
}
