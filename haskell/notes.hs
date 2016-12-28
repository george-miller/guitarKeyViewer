module Notes where

data Note = A | As | B | C | Cs | D | Ds | E | F | Fs | G | Gs deriving ( Read, Eq, Enum, Show )

data Scale = Major | Minor | Dominant deriving (Read, Show)

-- data Scale (a :: Note) = Major | Minor | Dominant deriving (Read, Show)

apply :: (a -> a) -> a -> Int -> a
apply _ a 0 = a
apply f a n = apply f (f a) (n - 1)

next :: Note -> Note
next Gs = A
next n = succ n

prev :: Note -> Note
prev A = Gs
prev n = pred n

whole :: Note -> Note
whole = next . next
half :: Note -> Note
half = next

generateScale :: Scale -> Note -> [Note]
generatorHelper :: [Int] -> Note -> [Note]
generatorHelper i n = map (apply half (prev n)) i
generateScale Major = generatorHelper [1, 3, 5, 6, 8, 10, 12]
generateScale Minor = generatorHelper [1, 3, 4, 6, 8, 9, 11]
generateScale Dominant = generatorHelper [1, 3, 5, 6, 8, 10, 11]

getScaleOfLength :: Scale -> Note -> Int -> [Note]
getScaleOfLength s n i
  | length scale >= i = take i scale
  | otherwise = scale ++ getScaleOfLength s n (i-length scale)
  where scale = generateScale s n
