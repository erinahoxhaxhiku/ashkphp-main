import { Button } from "@/components/ui/button"
import { Card, CardContent } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { Heart, Users, Shield, Award, ArrowRight, PawPrintIcon as Paw } from "lucide-react"
import Link from "next/link"
import Image from "next/image"

export default function HomePage() {
  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">
      {/* Navigation */}
      <nav className="bg-white/95 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <div className="flex items-center space-x-3">
              <div className="w-10 h-10 bg-gradient-to-br from-blue-600 to-green-600 rounded-full flex items-center justify-center">
                <Paw className="w-6 h-6 text-white" />
              </div>
              <span className="text-xl font-bold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
                Animal Shelter Kosovo
              </span>
            </div>
            <div className="hidden md:flex items-center space-x-8">
              <Link href="#home" className="text-gray-700 hover:text-blue-600 transition-colors">
                Home
              </Link>
              <Link href="#about" className="text-gray-700 hover:text-blue-600 transition-colors">
                About
              </Link>
              <Link href="#animals" className="text-gray-700 hover:text-blue-600 transition-colors">
                Animals
              </Link>
              <Link href="/volunteering" className="text-gray-700 hover:text-blue-600 transition-colors">
                Volunteer
              </Link>
              <Link href="/adoption" className="text-gray-700 hover:text-blue-600 transition-colors">
                Adopt
              </Link>
              <Button className="bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700">
                Contact Us
              </Button>
            </div>
          </div>
        </div>
      </nav>

      {/* Hero Section */}
      <section id="home" className="relative py-20 lg:py-32 overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-green-600/10"></div>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
          <div className="grid lg:grid-cols-2 gap-12 items-center">
            <div className="space-y-8">
              <Badge className="bg-blue-100 text-blue-800 hover:bg-blue-200">🐾 Saving Lives in Kosovo</Badge>
              <h1 className="text-4xl lg:text-6xl font-bold leading-tight">
                <span className="bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
                  Adopting & Loving
                </span>
                <br />
                Animals in Kosovo
              </h1>
              <p className="text-xl text-gray-600 leading-relaxed">
                Give a loving home to animals in need. Every adoption saves a life and brings joy to families across
                Kosovo.
              </p>
              <div className="flex flex-col sm:flex-row gap-4">
                <Button
                  size="lg"
                  className="bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700"
                >
                  Adopt Now <ArrowRight className="ml-2 w-5 h-5" />
                </Button>
                <Button size="lg" variant="outline" className="border-blue-600 text-blue-600 hover:bg-blue-50">
                  Learn More
                </Button>
              </div>
            </div>
            <div className="relative">
              <div className="absolute inset-0 bg-gradient-to-br from-blue-400 to-green-400 rounded-3xl transform rotate-6"></div>
              <Image
                src="/placeholder.svg?height=500&width=600"
                alt="Happy animals at shelter"
                width={600}
                height={500}
                className="relative rounded-3xl shadow-2xl object-cover"
              />
            </div>
          </div>
        </div>
      </section>

      {/* Services Section */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <Badge className="bg-green-100 text-green-800 hover:bg-green-200 mb-4">🐾 Our Services</Badge>
            <h2 className="text-3xl lg:text-4xl font-bold mb-4">
              Welcome to Our <span className="text-green-600">Animal Care</span> Community
            </h2>
            <p className="text-xl text-gray-600 max-w-3xl mx-auto">
              Dive into a world of whiskers, purrs, and heartwarming stories. Whether you're a pet lover, new pet
              parent, or just curious, we're here to help you every step of the way.
            </p>
          </div>

          <div className="grid md:grid-cols-3 gap-8">
            <Card className="group hover:shadow-xl transition-all duration-300 border-0 shadow-lg">
              <CardContent className="p-8">
                <div className="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                  <Heart className="w-8 h-8 text-white" />
                </div>
                <h3 className="text-xl font-bold mb-4">Animal Cafe</h3>
                <p className="text-gray-600 leading-relaxed">
                  Relax with adorable animals while enjoying your favorite drink. Our animal cafe is a peaceful haven
                  for animal lovers.
                </p>
              </CardContent>
            </Card>

            <Card className="group hover:shadow-xl transition-all duration-300 border-0 shadow-lg">
              <CardContent className="p-8">
                <div className="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                  <Shield className="w-8 h-8 text-white" />
                </div>
                <h3 className="text-xl font-bold mb-4">Animal Care</h3>
                <p className="text-gray-600 leading-relaxed">
                  Tips, tricks, and expert advice to ensure your animal friends are happy, healthy, and well cared for
                  every day.
                </p>
              </CardContent>
            </Card>

            <Card className="group hover:shadow-xl transition-all duration-300 border-0 shadow-lg">
              <CardContent className="p-8">
                <div className="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                  <Award className="w-8 h-8 text-white" />
                </div>
                <h3 className="text-xl font-bold mb-4">Pet Guide</h3>
                <p className="text-gray-600 leading-relaxed">
                  Our essential guide covers nutrition, grooming, and fun activities that strengthen the bond between
                  you and your pet.
                </p>
              </CardContent>
            </Card>
          </div>
        </div>
      </section>

      {/* Animals Gallery */}
      <section id="animals" className="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <Badge className="bg-blue-100 text-blue-800 hover:bg-blue-200 mb-4">🏠 Find Your Friend</Badge>
            <h2 className="text-3xl lg:text-4xl font-bold mb-4">
              Adoptable Animals in <span className="text-blue-600">Kosovo</span>
            </h2>
            <p className="text-xl text-gray-600">
              Discover some of the lovely animals you can adopt and give a forever home to.
            </p>
          </div>

          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            {[
              {
                name: "Polly the Parrot",
                description: "A friendly African Grey Parrot looking for a loving home.",
                image: "/placeholder.svg?height=300&width=400",
              },
              {
                name: "Max the Dog",
                description: "3 years old, playful and loyal companion waiting for adoption.",
                image: "/placeholder.svg?height=300&width=400",
              },
              {
                name: "Luna the Cat",
                description: "A gentle 2-year-old cat who loves cuddles and naps.",
                image: "/placeholder.svg?height=300&width=400",
              },
              {
                name: "Sammy the Snake",
                description: "A calm boa constrictor, perfect for reptile lovers.",
                image: "/placeholder.svg?height=300&width=400",
              },
              {
                name: "Bunny the Rabbit",
                description: "A fluffy rabbit who loves to hop around and play.",
                image: "/placeholder.svg?height=300&width=400",
              },
              {
                name: "Hammy the Hamster",
                description: "A tiny hamster with a big heart, ideal for small spaces.",
                image: "/placeholder.svg?height=300&width=400",
              },
            ].map((animal, index) => (
              <Card
                key={index}
                className="group hover:shadow-xl transition-all duration-300 border-0 shadow-lg overflow-hidden"
              >
                <div className="relative overflow-hidden">
                  <Image
                    src={animal.image || "/placeholder.svg"}
                    alt={animal.name}
                    width={400}
                    height={300}
                    className="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300"
                  />
                  <div className="absolute top-4 right-4">
                    <Badge className="bg-white/90 text-green-700">Available</Badge>
                  </div>
                </div>
                <CardContent className="p-6">
                  <h3 className="text-xl font-bold mb-2">{animal.name}</h3>
                  <p className="text-gray-600 mb-4">{animal.description}</p>
                  <Button className="w-full bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700">
                    Learn More
                  </Button>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Animal Care Section */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid lg:grid-cols-2 gap-12 items-center">
            <div className="relative">
              <div className="absolute inset-0 bg-gradient-to-br from-green-400 to-blue-400 rounded-3xl transform -rotate-6"></div>
              <Image
                src="/placeholder.svg?height=500&width=600"
                alt="Caring for animals"
                width={600}
                height={500}
                className="relative rounded-3xl shadow-2xl object-cover"
              />
            </div>
            <div className="space-y-6">
              <Badge className="bg-green-100 text-green-800 hover:bg-green-200">💚 Our Mission</Badge>
              <h2 className="text-3xl lg:text-4xl font-bold">
                Love. Respect. <span className="text-green-600">Care.</span>
              </h2>
              <p className="text-lg text-gray-600 leading-relaxed">
                Taking care of animals means more than just feeding them. It's about giving them love, attention, and a
                safe place to call home. Spend time playing with them, talk to them, and make sure they get regular
                checkups with a vet.
              </p>
              <p className="text-lg text-gray-600 leading-relaxed">
                Animals have emotions too — they feel joy, sadness, excitement, and fear. When you show them kindness,
                they give you unconditional love in return. Whether it's a wagging tail or a gentle purr, they'll thank
                you in their own way.
              </p>
              <Button
                size="lg"
                className="bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700"
              >
                Learn About Care <ArrowRight className="ml-2 w-5 h-5" />
              </Button>
            </div>
          </div>
        </div>
      </section>

      {/* About Us Section */}
      <section id="about" className="py-20 bg-gradient-to-br from-blue-50 to-green-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <Badge className="bg-purple-100 text-purple-800 hover:bg-purple-200 mb-4">👥 Meet Our Friends</Badge>
            <h2 className="text-3xl lg:text-4xl font-bold mb-4">About Us</h2>
            <p className="text-xl text-gray-600">Meet some of our amazing furry friends looking for a forever home!</p>
          </div>

          <div className="grid md:grid-cols-3 gap-8">
            {[
              {
                name: "Sophie",
                age: "3 years old",
                description: "I'm Sophie and I love to dance and cuddle on rainy days. Someone adopt me!",
                image: "/placeholder.svg?height=300&width=300",
              },
              {
                name: "Max",
                age: "5 years old",
                description:
                  "Hi, I'm Max! I'm a gentle soul who loves walks and belly rubs. Looking for a forever friend.",
                image: "/placeholder.svg?height=300&width=300",
              },
              {
                name: "Luna",
                age: "2 years old",
                description: "Luna here! I'm a curious kitty who loves chasing butterflies. Can I come home with you?",
                image: "/placeholder.svg?height=300&width=300",
              },
            ].map((animal, index) => (
              <Card
                key={index}
                className="group hover:shadow-xl transition-all duration-300 border-0 shadow-lg text-center"
              >
                <CardContent className="p-8">
                  <div className="relative mb-6">
                    <Image
                      src={animal.image || "/placeholder.svg"}
                      alt={animal.name}
                      width={300}
                      height={300}
                      className="w-32 h-32 rounded-full mx-auto object-cover group-hover:scale-110 transition-transform duration-300"
                    />
                    <div className="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                      <Badge className="bg-gradient-to-r from-blue-600 to-green-600 text-white">{animal.age}</Badge>
                    </div>
                  </div>
                  <h3 className="text-2xl font-bold mb-4">{animal.name}</h3>
                  <p className="text-gray-600 leading-relaxed mb-6">{animal.description}</p>
                  <Button className="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700">
                    Adopt {animal.name}
                  </Button>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-gray-900 text-white py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold mb-4">Want to Know More About Helping Animals?</h2>
            <p className="text-xl text-gray-300 mb-8 max-w-3xl mx-auto">
              We'd love to hear from you! Whether you have questions about adopting, volunteering, or learning more
              about stray animals in Kosovo — we're here to help.
            </p>
            <Button
              size="lg"
              className="bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700"
            >
              Contact Us
            </Button>
          </div>

          <div className="flex justify-center space-x-6 mb-8">
            <div className="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors cursor-pointer">
              <Users className="w-6 h-6" />
            </div>
            <div className="w-12 h-12 bg-pink-600 rounded-full flex items-center justify-center hover:bg-pink-700 transition-colors cursor-pointer">
              <Heart className="w-6 h-6" />
            </div>
            <div className="w-12 h-12 bg-blue-800 rounded-full flex items-center justify-center hover:bg-blue-900 transition-colors cursor-pointer">
              <Shield className="w-6 h-6" />
            </div>
          </div>

          <div className="text-center text-gray-400">
            <p>&copy; 2025 Animal Rescue Kosovo. All rights reserved.</p>
          </div>
        </div>
      </footer>
    </div>
  )
}
