    public function storeTemaBaju(Request $request)
        {
            $request->validate([
                'nama'       => 'required|string|max:255',
                'images.*'   => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'detail'     => 'required|string',
                'designer'   => 'required|string|max:255',
                'harga'      => 'required|numeric',
                'kode'       => 'required|string|max:50|unique:tema_baju,kode',
                'ukuran'     => 'required|string|max:100',
                'tipe'       => 'required|string|max:50',
            ]);

            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('tema_baju', 'public'); // storage/app/public/tema_baju
                    $imagePaths[] = $path;
                }
            }

            TemaBaju::create([
                'nama'     => $request->nama,
                'images'   => json_encode($imagePaths),
                'detail'   => $request->detail,
                'designer' => $request->designer,
                'harga'    => $request->harga,
                'kode'     => $request->kode,
                'ukuran'   => $request->ukuran,
                'tipe'     => $request->tipe,
                // 'order' & 'active' pakai default dari migration
            ]);

            return redirect()
                ->route('executive.catalogue.package')
                ->with('success', 'Tema baju berhasil ditambahkan.');
        }

        public function updateTemaBaju(Request $request, TemaBaju $temaBaju)
        {
            $request->validate([
                'nama'       => 'required|string|max:255',
                'images.*'   => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'detail'     => 'required|string',
                'designer'   => 'required|string|max:255',
                'harga'      => 'required|numeric',
                'kode'       => 'required|string|max:50|unique:tema_baju,kode,' . $temaBaju->id,
                'ukuran'     => 'required|string|max:100',
                'tipe'       => 'required|string|max:50',
            ]);

            $imagePaths = $temaBaju->images_array; // accessor dari model

            if ($request->hasFile('images')) {
                // hapus gambar lama
                foreach ($imagePaths as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }

                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('tema_baju', 'public');
                    $imagePaths[] = $path;
                }
            }

            $temaBaju->update([
                'nama'     => $request->nama,
                'images'   => json_encode($imagePaths),
                'detail'   => $request->detail,
                'designer' => $request->designer,
                'harga'    => $request->harga,
                'kode'     => $request->kode,
                'ukuran'   => $request->ukuran,
                'tipe'     => $request->tipe,
            ]);

            return redirect()
                ->route('executive.catalogue.package')
                ->with('success', 'Tema baju berhasil diperbarui.');
        }

        public function destroyTemaBaju(TemaBaju $temaBaju)
            {
                $imagePaths = $temaBaju->images_array ?? [];

                foreach ($imagePaths as $image) {
                    Storage::disk('public')->delete($image);
                }

                $temaBaju->delete();

                return redirect()
                    ->route('executive.catalogue.package')
                    ->with('success', 'Tema baju berhasil dihapus.');
            }

        public function storePackage(Request $request)
            {
                $request->validate([
                    'nama_paket' => 'required|string|max:255',
                    'deskripsi'  => 'nullable|array',
                    'deskripsi.*' => 'nullable|integer|exists:desc_packages,id',
                    'harga'      => 'required|numeric',
                    'durasi'     => 'nullable|integer',
                    'discount'   => 'nullable|numeric|min:0|max:100',
                    'notes'      => 'nullable|string',
                    'konsep'     => 'nullable|array',
                    'konsep.*'   => 'nullable|integer|exists:konsep_attires,id',
                    'label_id'   => 'nullable|array',
                    'label_id.*' => 'nullable|integer|exists:package_labels,id',
                    'tac_ids'    => 'nullable|array',
                    'tac_ids.*'  => 'nullable|integer|exists:tac_packages,id',
                    'rules'      => 'nullable|string',
                    'attire_ids' => 'nullable|array',
                    'attire_ids.*' => 'nullable|integer|exists:tema_baju,id',
                    'images'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                ]);

                $imagePath = null;
                if ($request->hasFile('images')) {
                    // simpan ke storage/app/public/packages
                    $imagePath = $request->file('images')->store('packages', 'public');
                }

                $maxOrder = Package::max('order') ?? 0;

                Package::create([
                    'nama_paket' => $request->nama_paket,
                    'deskripsi'  => $request->input('deskripsi', []),
                    'harga'      => $request->harga,
                    'durasi'     => $request->durasi,
                    'discount'   => $request->discount ?? 0,
                    'notes'      => $request->notes,
                    'konsep'     => $request->input('konsep', []),
                    'label_id'   => $request->input('label_id', []),
                    'rules'      => $request->rules,
                    'tac_ids'    => $request->input('tac_ids', []),
                    'attire_ids' => $request->input('attire_ids', []),
                    'images'     => $imagePath,
                    'order'      => $maxOrder + 1,
                    // 'active' pakai default dari migration
                ]);

                return redirect()
                    ->route('executive.catalogue.package')
                    ->with('success', 'Package berhasil ditambahkan.');
            }

        public function updatePackage(Request $request, Package $package)
            {
                $request->validate([
                    'nama_paket' => 'required|string|max:255',
                    'deskripsi'  => 'nullable|array',
                    'deskripsi.*' => 'nullable|integer|exists:desc_packages,id',
                    'harga'      => 'required|numeric',
                    'durasi'     => 'nullable|integer',
                    'discount'   => 'nullable|numeric|min:0|max:100',
                    'notes'      => 'nullable|string',
                    'konsep'     => 'nullable|array',
                    'konsep.*'   => 'nullable|integer|exists:konsep_attires,id',
                    'label_id'   => 'nullable|array',
                    'label_id.*' => 'nullable|integer|exists:package_labels,id',
                    'tac_ids'    => 'nullable|array',
                    'tac_ids.*'  => 'nullable|integer|exists:tac_packages,id',
                    'rules'      => 'nullable|string',
                    'attire_ids' => 'nullable|array',
                    'attire_ids.*' => 'nullable|integer|exists:tema_baju,id',
                    'images'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                ]);

                $imagePath = $package->images;

                // kalau ada upload gambar baru => hapus lama, simpan baru
                if ($request->hasFile('images')) {
                    if ($imagePath) {
                        Storage::disk('public')->delete($imagePath);
                    }
                    $imagePath = $request->file('images')->store('packages', 'public');
                }

                $package->update([
                    'nama_paket' => $request->nama_paket,
                    'deskripsi'  => $request->input('deskripsi', []),
                    'harga'      => $request->harga,
                    'durasi'     => $request->durasi,
                    'discount'   => $request->discount ?? 0,
                    'notes'      => $request->notes,
                    'konsep'     => $request->input('konsep', []),
                    'label_id'   => $request->input('label_id', []),
                    'rules'      => $request->rules,
                    'tac_ids'    => $request->input('tac_ids', []),
                    'attire_ids' => $request->input('attire_ids', []),
                    'images'     => $imagePath,
                    // 'order' & 'active' kalau mau diubah, bisa ditambah di sini
                ]);

                return redirect()
                    ->route('executive.catalogue.package')
                    ->with('success', 'Package berhasil diperbarui.');
            }

        public function destroyPackage(Package $package)
            {
                // hapus file gambar di storage, kalau ada
                if ($package->images) {
                    Storage::disk('public')->delete($package->images);
                }

                $package->delete();

                return redirect()
                    ->route('executive.catalogue.package')
                    ->with('success', 'Package berhasil dihapus.');
            }